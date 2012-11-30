<?php defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
include_once dirname(__FILE__) . '/kampinfomodelparent.php';

/**
 * KampInfo Hitkiezer Model.
 */
class KampInfoModelHitkiezer extends KampInfoModelParent {

		public function getJaar() {
		return JRequest :: getInt('jaar');
	}

	public function getProject() {
		$filterJaar = $this->getJaar();

		$project = $this->getHitProjectRow($filterJaar);
		$project->hitPlaatsen = $this->getHitPlaatsen($filterJaar);
		
		$project->gebruikteIconen = $this->getIconenLijstJSON();
		
		$iconenLookup = array();
		foreach ($project->gebruikteIconen as $icon) {
			$iconenLookup[$icon->bestandsnaam] = $icon;
		}
		
		foreach ($project->hitPlaatsen as $plaats) {
			$plaats->kampen = $this->getHitKampenJSON($plaats->code, $iconenLookup);
		}
		return $project;
	}

	public function getIconenLijstJSON() {
		$db = JFactory :: getDBO();

		$query = $db->getQuery(true);
		$query->select('i.volgorde, i.bestandsnaam, i.tekst');
		$query->from('#__kampinfo_hiticon i');

		$db->setQuery($query);
		$icons = $db->loadObjectList();
		
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
		
		return $icons;
	}
	function getHitProjectRow($jaar) {
		$db = JFactory :: getDBO();

		$query = $db->getQuery(true);
		$query->select('jaar, vrijdag, maandag');
		$query->from('#__kampinfo_hitproject p');
		$query->where('(p.jaar = ' . (int) ($db->getEscaped($jaar)) . ')');

		$db->setQuery($query);
		$project = $db->loadObjectList();

		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
		return $project[0];
	}

	function getHitPlaatsen($jaar) {
		$db = JFactory :: getDBO();

		$query = $db->getQuery(true);
		$query->select('deelnemersnummer,naam,code');
		$query->from('#__kampinfo_hitsite s');
		$query->where('(s.jaar = ' . (int) ($db->getEscaped($jaar)) . ')');
		$query->order('s.naam');

		$db->setQuery($query);
		$plaatsenInJaar = $db->loadObjectList();
		return $plaatsenInJaar;
	}

	function getHitKampenJSON($plaats, $iconenLookup) {
		$db = JFactory :: getDBO();

		$query = $db->getQuery(true);
		$query->select('c.deelnemersnummer,c.naam,c.shantiformuliernummer,c.minimumLeeftijd,c.maximumLeeftijd,c.deelnamekosten,c.minimumAantalDeelnemers,c.maximumAantalDeelnemers,c.aantalDeelnemers,c.gereserveerd,c.subgroepsamenstellingMinimum,c.icoontjes');
		$query->from('#__kampinfo_hitcamp c');
		$query->where('(c.hitsite = ' . $db->quote($db->getEscaped($plaats)) . ')');
		$query->order('c.naam');

		$db->setQuery($query);
		$kampenInPlaats = $db->loadObjectList();

		foreach ($kampenInPlaats as $kamp) {
			$icoontjes = explode(',', $kamp->icoontjes);
			$nieuweIcoontjes = array();
			foreach ($icoontjes as $icoon) {
				$nieuweIcoontjes[] = $iconenLookup[$icoon];
			}
			$kamp->iconen = $nieuweIcoontjes;
		}
		return $kampenInPlaats;
	}
 
}