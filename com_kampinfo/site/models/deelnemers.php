<?php defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
include_once dirname(__FILE__) . '/kampinfomodelparent.php';

/**
 * KampInfo Deelnemers Model
 */
class KampInfoModelDeelnemers extends KampInfoModelParent {

	public function getJaar() {
		$input = JFactory::getApplication()->input;
		return $input->getInt('jaar', date("Y"));
	}

	public function getProject() {
		$filterJaar = $this->getJaar();
		
		$project = $this->getHitProjectRow($filterJaar);
		$project->laatstBijgewerktOp = $this->getLaatstBijgewerktOp($filterJaar);
		$project->laatsteInschrijvingOp = $this->getLaatsteInschrijvingOp($filterJaar);
		
		$kampen = $this->getDeelnemerInschrijvingen($filterJaar, $project->inschrijvingStartdatum, $project->laatsteInschrijvingOp);

		$plaatsen = array();
		foreach ($kampen as $kamp) {
			if (empty($plaatsen[$kamp->plaats])) {
				$plaats = new StdClass();
				$plaats->naam = $kamp->plaats;
				$plaats->kampen = array();
				$plaatsen[$kamp->plaats] = $plaats;
			}
			array_push($plaatsen[$kamp->plaats]->kampen, $kamp);
		}
		$project->plaatsen = $plaatsen;
		return $project;
		
		// project {
		//   jaar: 2013
		// , plaatsen: [
		//   {
		//		  naam: 'Alphen'
		//		, kampen: [
		//			{	naam: 'kamp1'
		//			,	Jan01: 1
		//			,	Jan02: 3
		//			,	Jan03: 1
		//			}
		//			,
		//			{	naam: 'kamp2'
		//			,	Jan03: 2
		//			}
		//		]
		//	 }
		//   , {n}
		//   ]
		// } 
	}

	
	function getDeelnemerInschrijvingen($jaar, $inschrijvingStartdatum, $inschrijvingEinddatum) {
		$inschrijvingStartdatum = new DateTime($inschrijvingStartdatum);
		$inschrijvingEinddatum= new DateTime($inschrijvingEinddatum);
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('s.naam as plaats, c.naam');
		
		$laatsteDatum = strtotime($inschrijvingEinddatum->format('Y-m-d'));
		for($i = $inschrijvingStartdatum; 
			strtotime($i->format('Y-m-d'))
			 <= $laatsteDatum;
			$i->add(new DateInterval('P1D'))
		) {
			$date = $i->format('Y-m-d');
			$col = $i->format('Md');
			$query->select("sum(if(d.datumInschrijving='$date', 1,0)) as $col");
		}
		$query->from('#__kampinfo_hitcamp c');
		$query->leftJoin('#__kampinfo_hitsite s on (c.hitsite_id = s.id)');
		$query->leftJoin('#__kampinfo_hitproject p on (s.hitproject_id = p.id)');
		$query->leftJoin('#__kampinfo_deelnemers d on (soundex(left(d.hitcamp, 21)) = soundex(left(c.naam,21)) and d.jaar = p.jaar and lower(d.hitsite) = lower(s.naam))');
		$query->where('(p.jaar = ' . (int) ($db->escape($jaar)) . ')');
		$query->group(" d.hitsite, d.hitcamp ");
		$query->order('c.hitsite, c.naam');
		$db->setQuery($query);
		
		$plaatsenInJaar = $db->loadObjectList();
		return $plaatsenInJaar;
	}
	
	function getLaatsteInschrijvingOp($jaar) {
		$db = JFactory::getDBO();
	
		$query = $db->getQuery(true);
		$query->select('max(d.datumInschrijving) as laatsteInschrijving');
		$query->from('#__kampinfo_deelnemers d');
		$query->where('(d.jaar = ' . (int) ($db->escape($jaar)) . ')');
		
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		return $result[0]->laatsteInschrijving;
	}

	function getHitProjectRow($jaar) {
		$db = JFactory::getDBO();
	
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__kampinfo_hitproject p');
		$query->where('(p.jaar = ' . (int) ($db->escape($jaar)) . ')');
	
		$db->setQuery($query);
		$project = $db->loadObjectList();
	
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		return $project[0];
	}

}