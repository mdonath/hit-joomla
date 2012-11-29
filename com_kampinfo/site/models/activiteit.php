<?php defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
include_once dirname(__FILE__).'/kampinfomodelparent.php';

/**
 * KampInfo Activiteit Model
 */
class KampInfoModelActiviteit extends KampInfoModelParent {

	public function getActiviteit() {
		$jaar = JRequest :: getInt('jaar');
		$deelnemersnummer = JRequest :: getInt('deelnemersnummer');
		if (!empty($jaar) and !empty($deelnemersnummer)) {
			$activiteit = $this->getHitKampById($jaar, $deelnemersnummer);
		} else {
			JError :: raiseWarning(404, "Kamp met deelnemersnummer $deelnemersnummer bestaat niet in $jaar.");
		}
	 	return $activiteit;
	}

	public function getIconenLijst() {
		
	}

	function getHitKampById($jaar, $deelnemersnummer) {
		$db = JFactory :: getDBO();

		$query = $db->getQuery(true);
		$query->select('c.*');
		$query->from('#__kampinfo_hitcamp c');

		$query->select('s.naam as plaats, s.id as hitsite_id');
		$query->join('LEFT', '#__kampinfo_hitsite AS s ON c.hitsite=s.code');

		$query->select('p.jaar as jaar, p.id as hitproject_id');
		$query->join('LEFT', '#__kampinfo_hitproject AS p ON s.jaar=p.jaar');

		$query->where('(s.jaar = ' . (int)($db->getEscaped($jaar)) . ')');
		$query->where('(c.deelnemersnummer = ' . (int)($db->getEscaped($deelnemersnummer)) . ')');

		$db->setQuery($query);
		$activiteiten = $db->loadObjectList();
		
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
		
		if (count($activiteiten) != 1) {
			JError :: raiseWarning(500, "0 of meer dan 1 gevonden met deelnemersnummer $deelnemersnummer in jaar $jaar.");
		}
		$activiteit = $activiteiten[0];

		$activiteit->icoontjes =  $this->createIcons($activiteit->icoontjes);
		$activiteit->activiteitengebieden = $this->createActiviteitengebieden($activiteit->activiteitengebieden); 
		return $activiteit;
	}
}