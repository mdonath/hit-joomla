<?php


// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * KampInfo Hitkiezer Model
 */
class KampInfoModelHitkiezer extends JModelItem {

	public function getJaar() {
		return JRequest :: getInt('jaar');
	}

	public function getProject() {
		$filterJaar = $this->getJaar();
		
		$project = $this->getHitKampen($filterJaar);
		
	 	return $project;
	}

	function getHitKampen($jaar) {
		$db = JFactory :: getDBO();

		$query = $db->getQuery(true);
		$query->select('c.id, c.naam, c.deelnemersnummer, c.minimumLeeftijd, c.maximumLeeftijd, c.deelnamekosten, c.groep, c.websiteTekst');
		$query->from('#__kampinfo_hitcamp c');

		$query->select('s.naam as plaats, s.id as hitsite_id');
		$query->join('LEFT', '#__kampinfo_hitsite AS s ON c.hitsite=s.code');

		$query->select('p.jaar as jaar, p.id as hitproject_id');
		$query->join('LEFT', '#__kampinfo_hitproject AS p ON s.jaar=p.jaar');

		if (!empty ($jaar)) {
			$query->where('(p.jaar = ' . (int)($db->getEscaped($jaar)) . ')');
		}

		$db->setQuery($query);
		$kampenInPlaats = $db->loadObjectList();
		
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
		return $kampenInPlaats;
	}
 
}