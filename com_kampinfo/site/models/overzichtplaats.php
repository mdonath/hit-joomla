<?php


// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * KampInfo Overzichtplaats Model
 */
class KampInfoModelOverzichtplaats extends JModelItem {

	public function getPlaats() {
		$filterPlaatscode = JRequest :: getString('plaats');
		
		$plaats = $this->getHitPlaats($filterPlaatscode);
		$plaats->kampen = $this->getHitKampen($plaats->code);

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
	
	function getHitKampen($plaats) {
		$db = JFactory :: getDBO();

		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__kampinfo_hitcamp c');
		$query->where('(c.hitsite = ' . $db->quote($db->getEscaped($plaats)) . ')');
		$query->order('c.naam');

		$db->setQuery($query);
		$kampenInPlaats = $db->loadObjectList();
		return $kampenInPlaats;
	}
}