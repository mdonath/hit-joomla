<?php


// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * KampInfo Overzicht Model
 */
class KampInfoModelOverzicht extends JModelItem {

	public function getJaar() {
		return JRequest :: getInt('jaar');
	}

	public function getProject() {
		$filterJaar = $this->getJaar();
		
		$project = $this->getHitProjectRow($filterJaar);
		$project->plaatsen = $this->getHitPlaatsen($filterJaar);
		foreach ($project->plaatsen as $plaats) {
			$plaats->kampen = $this->getHitKampen($plaats->code);
		}
		return $project;
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

	function getHitPlaatsen($jaar) {
		$db = JFactory :: getDBO();

		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__kampinfo_hitsite s');
		$query->where('(s.jaar = ' . (int)($db->getEscaped($jaar)) . ')');
		$query->order('s.naam');

		$db->setQuery($query);
		$plaatsenInJaar = $db->loadObjectList();
		return $plaatsenInJaar;
	}

	function getHitProjectRow($jaar) {
		$db = JFactory :: getDBO();

		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__kampinfo_hitproject p');
		$query->where('(p.jaar = ' . (int)($db->getEscaped($jaar)) . ')');

		$db->setQuery($query);
		$project = $db->loadObjectList();
		
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
		return $project[0];
	}
	
	function dummy() {
		$db = JFactory :: getDBO();
		$query = $db->getQuery(true);
		$query->select('c.id, c.naam');
		$query->from('#__kampinfo_hitcamp c');

		$query->select('s.naam as plaats, s.id as hitsite_id');
		$query->join('LEFT', '#__kampinfo_hitsite AS s ON c.hitsite=s.code');

		$query->select('p.jaar as jaar, p.id as hitproject_id');
		$query->join('LEFT', '#__kampinfo_hitproject AS p ON s.jaar=p.jaar');

		if (!empty ($filterJaar)) {
			$query->where('(p.jaar = ' . (int)($db->getEscaped($filterJaar)) . ')');
		}
		
		$db->setQuery($query);

		$kampen = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
		
	}
}