<?php

use Joomla\CMS\Factory;

defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * KampInfo Activiteit Model
 */
abstract class KampInfoModelParent extends JModelItem {

	function getHitProject($projectId) {
		$db = Factory::getDBO();
	
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__kampinfo_hitproject p');
		$query->where('(p.id = ' . (int) ($db->escape($projectId)) . ')');
	
		$db->setQuery($query);
		$project = $db->loadObjectList();
	
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		return $project[0];
	}
	
	function getHitPlaatsen($projectId) {
		$db = Factory::getDBO();
	
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__kampinfo_hitsite s');
		$query->where('(s.hitproject_id = ' . (int) ($db->escape($projectId)) . ')');
		$query->where('(s.published=1 and s.akkoordHitPlaats=1)');
		$query->order('s.naam');
	
		$db->setQuery($query);
		$plaatsenInJaar = $db->loadObjectList();
		return $plaatsenInJaar;
	}

	function getHitPlaats($hitsiteId) {
		$db = Factory::getDBO();
	
		$query = $db->getQuery(true)
			-> select('s.*, p.jaar')
			-> from('#__kampinfo_hitsite s')
			-> join('LEFT', '#__kampinfo_hitproject p ON (s.hitproject_id = p.id)')
			-> where('(s.id = ' . (int)($db->escape($hitsiteId)) . ')');
	
		$db->setQuery($query);
		$plaats = $db->loadObjectList();
		return $plaats[0];
	}

	function getHitKampen($hitsiteId, $iconenLijst) {
		$db = Factory::getDBO();

		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__kampinfo_hitcamp c');
		$query->where('(c.hitsite_id = ' . (int)($db->escape($hitsiteId)) . ')');
		$query->where('(c.published = 1 and c.akkoordHitKamp=1 and c.akkoordHitPlaats=1)');
		$query->order('c.minimumLeeftijd, c.maximumLeeftijd, c.naam');

		$db->setQuery($query);
		$kampenInPlaats = $db->loadObjectList();

		foreach ($kampenInPlaats as $kamp) {
			$kamp->icoontjes = $this->explodeIcoontjes($kamp, $iconenLijst);
		}
		return $kampenInPlaats;
	}

	protected function getLaatstBijgewerktOp($jaar) {
		$db = Factory::getDBO();
		
		$query = $db->getQuery(true);
		$query->select('max(bijgewerktOp) as bijgewerktOp');
		$query->from('#__kampinfo_downloads');
		$query->where('(jaar = ' . (int)($db->escape($jaar)) .')');
		$query->where('(soort = '. $db->quote($db->escape('INSC')) .')');
		
		$db->setQuery($query);
		$bijgewerktOp = $db->loadResult();
		
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		
		return $bijgewerktOp;
	}

	/**
	 * @param $namen - comma separated string
	 */
	public function createIcons($namen) {
		$db = Factory::getDBO();

		$query = $db->getQuery(true);
		$query->select('i.bestandsnaam as naam, i.tekst, i.volgorde');
		$query->from('#__kampinfo_hiticon i');

		$values=array();
		foreach(explode(',', $namen) as $naam) {
			$values[] = $db->quote($db->escape($naam));
		}
		$query->where('i.bestandsnaam in (' . implode(',', $values) . ')');
		$query->order('i.volgorde');
		
		$db->setQuery($query);
		$icons = $db->loadObjectList();
		
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		
		return $icons;
	}

	function createActiviteitengebieden($activiteitengebieden) {
		$activiteitengebieden = explode(',', $activiteitengebieden);
		$options = KampInfoHelper::getActivityAreaOptions();
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
		foreach (KampInfoHelper::getTargetgroupOptions() as $v) {
			$doelgroepenLookup[$v->value] = $v->text;
		}
		$result = '';
		$sep = '';
		foreach (explode(',', $doelgroepen) as $doelgroep) {
			if (!empty($doelgroep)) {
				$result .= $sep . $doelgroepenLookup[$doelgroep];
				$sep = ', ';
			}
		}
		return $result;
	}

	function explodeIcoontjes($kamp, $iconenLijst) {
		if (empty($iconenLijst)) {
			$iconenLijst = $this->getIconenLijst();
		}

		$nieuweIcoontjes = array();

		$aantalNachten = KampInfoHelper::aantalOvernachtingen($kamp);
		$overnachtingKey = "aantalnacht".$aantalNachten;
		// if (array_key_exists($overnachtingKey, $iconenLijst)) {
			$nieuweIcoontjes[] = $iconenLijst[$overnachtingKey];
		// }
		if (!empty($kamp->icoontjes)) {
			$icoontjes = explode(',', $kamp->icoontjes);
			foreach ($icoontjes as $icoon) {
				if (array_key_exists($icoon, $iconenLijst)) {
					$nieuweIcoontjes[] = $iconenLijst[$icoon];
				}
			}
		}
		return $nieuweIcoontjes;
	}

	public function getIconenLijst() {
		$db = Factory::getDBO();

		$query = $db->getQuery(true);
		$query->select('i.bestandsnaam as naam, i.tekst, i.volgorde, i.soort');
		$query->from('#__kampinfo_hiticon i');

		$db->setQuery($query);
		$icons = $db->loadObjectList();
		
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		
		$result = array();
		foreach ($icons as $icon) {
			$i = new stdClass();
			$i->naam = $icon->naam;
			$i->tekst = $icon->tekst;
			$i->volgorde = $icon->volgorde;
			$i->soort = $icon->soort;
			$result[$icon->naam] = $i;
		}
		return $result;
	}

}