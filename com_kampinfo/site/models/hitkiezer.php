<?php defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
include_once dirname(__FILE__) . '/kampinfomodelparent.php';

/**
 * KampInfo Hitkiezer Model.
 */
class KampInfoModelHitkiezer extends KampInfoModelParent {

	public function getProject() {
		$projectId = JRequest::getInt('hitproject_id');

		$project = $this->getHitProject($projectId);
		$project->hitPlaatsen = $this->getHitPlaatsen($projectId);
		
		$project->gebruikteIconen = $this->getIconenLijstJSON();
		
		$iconenLookup = array();
		foreach ($project->gebruikteIconen as $icon) {
			$iconenLookup[$icon->bestandsnaam] = $icon;
		}
		
		foreach ($project->hitPlaatsen as $plaats) {
			$plaats->kampen = $this->getHitKampenJSON($plaats->id, $iconenLookup);
		}
		return $project;
	}

	public function getIconenLijstJSON() {
		$db = JFactory::getDBO();

		$query = $db->getQuery(true);
		$query->select('i.volgorde, i.bestandsnaam, i.tekst');
		$query->from('#__kampinfo_hiticon i');

		$db->setQuery($query);
		$icons = $db->loadObjectList();
		
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		
		return $icons;
	}

	function getHitKampenJSON($hitsiteId, $iconenLookup) {
		$db = JFactory::getDBO();

		$query = $db->getQuery(true);
		$query->select('c.naam,c.shantiFormuliernummer,c.minimumLeeftijd,c.maximumLeeftijd,c.deelnamekosten,c.minimumAantalDeelnemers,c.maximumAantalDeelnemers,c.aantalDeelnemers,c.gereserveerd,c.subgroepsamenstellingMinimum,c.aantalSubgroepen,c.maximumAantalSubgroepjes,c.icoontjes,c.margeAantalDagenTeJong,c.margeAantalDagenTeOud, c.startDatumTijd, c.eindDatumTijd, c.isouderkind');
		$query->from('#__kampinfo_hitcamp c');
		$query->where('(c.hitsite_id = ' . (int)($db->escape($hitsiteId)) . ')');
		$query->where('(c.published=1 and c.akkoordHitKamp=1 and c.akkoordHitPlaats=1)');
		$query->order('c.naam');

		$db->setQuery($query);
		$kampenInPlaats = $db->loadObjectList();

		foreach ($kampenInPlaats as $kamp) {
			$nieuweIcoontjes = array();
			if (!empty($kamp->icoontjes)) {
				$aantalNachten = KampInfoHelper::aantalOvernachtingen($kamp);
				$kamp->icoontjes = "aantalnacht{$aantalNachten},". $kamp->icoontjes;
				$icoontjes = explode(',', $kamp->icoontjes);
				foreach ($icoontjes as $icoon) {
					$nieuweIcoontjes[] = $iconenLookup[$icoon];
				}
			}
			$kamp->iconen = $nieuweIcoontjes;
			unset($kamp->icoontjes);
		}
		return $kampenInPlaats;
	}
 
}