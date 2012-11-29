<?php
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
include_once dirname(__FILE__) . '/kampinfomodelparent.php';

/**
 * KampInfo Overzichtplaats Model
 */
class KampInfoModelOverzichtplaats extends KampInfoModelParent {

	public function getPlaats() {
		$filterPlaatscode = JRequest :: getString('plaats');

		$plaats = $this->getHitPlaats($filterPlaatscode);

		$iconenLijst = $this->getIconenLijst();
		$plaats->kampen = $this->getHitKampen($plaats->code, $iconenLijst);

		return $plaats;
	}

	function getHitPlaats($filterPlaatscode) {
		$db = JFactory :: getDBO();

		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__kampinfo_hitsite s');
		$query->where('(s.code = ' . $db->quote($db->getEscaped($filterPlaatscode)) . ')');

		$db->setQuery($query);
		$plaats = $db->loadObjectList();
		return $plaats[0];
	}

}