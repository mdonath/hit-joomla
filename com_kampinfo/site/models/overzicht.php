<?php defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
include_once dirname(__FILE__) . '/kampinfomodelparent.php';

/**
 * KampInfo Overzicht Model
 */
class KampInfoModelOverzicht extends KampInfoModelParent {

	public function getJaar() {
		return JRequest :: getInt('jaar');
	}

	public function getProject() {
		$filterJaar = $this->getJaar();

		$project = $this->getHitProjectRow($filterJaar);
		$project->plaatsen = $this->getHitPlaatsen($filterJaar);

		$iconenLijst = $this->getIconenLijst();
		foreach ($project->plaatsen as $plaats) {
			$plaats->kampen = $this->getHitKampen($plaats->code, $iconenLijst);
		}
		return $project;
	}

	function getHitProjectRow($jaar) {
		$db = JFactory :: getDBO();

		$query = $db->getQuery(true);
		$query->select('*');
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
		$query->select('*');
		$query->from('#__kampinfo_hitsite s');
		$query->where('(s.jaar = ' . (int) ($db->getEscaped($jaar)) . ')');
		$query->order('s.naam');

		$db->setQuery($query);
		$plaatsenInJaar = $db->loadObjectList();
		return $plaatsenInJaar;
	}



}