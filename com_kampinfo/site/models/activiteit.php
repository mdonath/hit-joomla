<?php defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
include_once dirname(__FILE__).'/kampinfomodelparent.php';

/**
 * KampInfo Activiteit Model
 */
class KampInfoModelActiviteit extends KampInfoModelParent {

	public function getActiviteit() {
		$hitcampId = JRequest::getInt('hitcamp_id');
		if (!empty($hitcampId)) {
			$activiteit = $this->getHitKampById($hitcampId);
		} else {
			JError::raiseWarning(404, "Kamp niet gevonden?!");
		}
	 	return $activiteit;
	}

	public function getIconenLijst() {
		
	}

	function getHitKampById($hitcampId) {
		$db = JFactory::getDBO();

		$query = $db->getQuery(true);
		$query->select('c.*');
		$query->from('#__kampinfo_hitcamp c');
		$query->where('(c.id = ' . (int)($db->escape($hitcampId)) . ')');

		$query->select('s.naam as plaats, s.id as hitsite_id');
		$query->join('LEFT', '#__kampinfo_hitsite AS s ON c.hitsite_id=s.id');

		$query->select('p.jaar as jaar, p.id as hitproject_id, p.inschrijvingStartdatum as startInschrijving ');
		$query->join('LEFT', '#__kampinfo_hitproject AS p ON s.hitproject_id=p.id');

		$db->setQuery($query);
		$activiteiten = $db->loadObjectList();
		
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		
		if (count($activiteiten) != 1) {
			JError::raiseWarning(500, "0 of meer dan 1 gevonden met id $hitcampId in jaar $jaar.");
		}
		$activiteit = $activiteiten[0];

		$activiteit->icoontjes =  $this->createIcons($activiteit->icoontjes);
		$activiteit->activiteitengebieden = $this->createActiviteitengebieden($activiteit->activiteitengebieden);
		$activiteit->doelgroepen = $this->createDoelgroepen($activiteit->doelgroepen);
		return $activiteit;
	}
}