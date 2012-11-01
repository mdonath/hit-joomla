<?php


// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * KampInfo Activiteit Model
 */
class KampInfoModelActiviteit extends JModelItem {

	public function getActiviteit() {
		$id = JRequest :: getInt('id');
		
		$activiteit = $this->getHitKamp($id);
		
	 	return $activiteit;
	}

	function getHitKamp($id) {
		$db = JFactory :: getDBO();

		$query = $db->getQuery(true);
		$query->select('c.*');
		$query->from('#__kampinfo_hitcamp c');

		$query->select('s.naam as plaats, s.id as hitsite_id');
		$query->join('LEFT', '#__kampinfo_hitsite AS s ON c.hitsite=s.code');

		$query->select('p.jaar as jaar, p.id as hitproject_id');
		$query->join('LEFT', '#__kampinfo_hitproject AS p ON s.jaar=p.jaar');

		if (!empty ($id)) {
			$query->where('(c.id= ' . (int)($db->getEscaped($id)) . ')');
		}

		$db->setQuery($query);
		$activiteiten = $db->loadObjectList();
		
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
		
		if (count($activiteiten) != 1) {
			JError :: raiseWarning(500, '0 of meer dan 1 gevonden met id '. $id);
		}
		return $activiteiten[0];
	}
 
}