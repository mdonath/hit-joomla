<?php defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * KampInfo Activiteit Model
 */
abstract class KampInfoModelParent extends JModelItem {

	function getHitKampen($plaats, $iconenLijst) {
		$db = JFactory :: getDBO();

		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__kampinfo_hitcamp c');
		$query->where('(c.hitsite = ' . $db->quote($db->getEscaped($plaats)) . ')');
		$query->order('c.naam');

		$db->setQuery($query);
		$kampenInPlaats = $db->loadObjectList();

		foreach ($kampenInPlaats as $kamp) {
			$kamp->icoontjes = $this->explodeIcoontjes($kamp, $iconenLijst);
		}
		return $kampenInPlaats;
	}

	protected function getLaatstBijgewerktOp($jaar) {
		$db = JFactory :: getDBO();
		
		$query = $db->getQuery(true);
		$query->select('max(bijgewerktOp) as bijgewerktOp');
		$query->from('#__kampinfo_downloads');
		$query->where('(jaar = ' . (int)($db->getEscaped($jaar)) .')');
		$query->where('(soort = '. $db->quote($db->getEscaped('INSC')) .')');
		
		$db->setQuery($query);
		$bijgewerktOp = $db->loadResult();
		
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
		
		return $bijgewerktOp;
	}

	/**
	 * @param $namen - comma separated string
	 */
	public function createIcons($namen) {
		$db = JFactory :: getDBO();

		$query = $db->getQuery(true);
		$query->select('i.bestandsnaam as naam, i.tekst');
		$query->from('#__kampinfo_hiticon i');

		$values=array();
		foreach(explode(',', $namen) as $naam) {
			$values[] = $db->quote($db->getEscaped($naam));
		}
		$query->where('i.bestandsnaam in (' . implode(',', $values) . ')');
		$query->order('i.volgorde');
		
		$db->setQuery($query);
		$icons = $db->loadObjectList();
		
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
		
		return $icons;
	}

	function createActiviteitengebieden($activiteitengebieden) {
		$activiteitengebieden = explode(',', $activiteitengebieden);
		$options = KampInfoHelper :: getActivityAreaOptions();
		$result = array();
		foreach ($activiteitengebieden as $gebied) {
			foreach($options as $option) {
				if ($option->value == $gebied) {
					$result[] = $option;
				}
			}
		}
		return $result;		
	}
	
	function createDoelgroepen($doelgroepen) {
		$doelgroepenLookup = array();
		foreach (KampInfoHelper :: getTargetgroupOptions() as $v) {
			$doelgroepenLookup[$v->value] = $v->text;
		}
		$result = '';
		$sep = '';
		foreach (explode(',', $doelgroepen) as $doelgroep) {
			$result .= $sep . $doelgroepenLookup[$doelgroep];
			$sep = ', ';
		}
		return $result;
	}

	function explodeIcoontjes($kamp, $iconenLijst) {
		if (empty($iconenLijst)) {
			$iconenLijst = $this->getIconenLijst();
		}
		$icoontjes = explode(',', $kamp->icoontjes);
		$nieuweIcoontjes = array();
		foreach ($icoontjes as $icoon) {
			$nieuweIcoontjes[] = $iconenLijst[$icoon];
		}
		return $nieuweIcoontjes;
	}


	public function getIconenLijst() {
		$db = JFactory :: getDBO();

		$query = $db->getQuery(true);
		$query->select('i.bestandsnaam as naam, i.tekst');
		$query->from('#__kampinfo_hiticon i');

		$db->setQuery($query);
		$icons = $db->loadObjectList();
		
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
		
		$result = array();
		foreach ($icons as $icon) {
			$i = new stdClass();
			$i->naam = $icon->naam;
			$i->tekst = $icon->tekst;
			$result[$icon->naam] = $i;
		}
		return $result;
	}

}