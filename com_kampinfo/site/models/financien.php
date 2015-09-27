<?php defined('_JEXEC') or die('Restricted access');

include_once dirname(__FILE__) . '/kampinfomodelparent.php';

/**
 * KampInfo Financien Model.
 */
class KampInfoModelFinancien extends KampInfoModelParent {

	public function getFinancienData() {
		$params =JComponentHelper::getParams('com_kampinfo');
		$projectId = $params->get('huidigeActieveJaar');
		return $this->getData($projectId);
	}

	private function getData($projectId) {
		$db = JFactory::getDBO();
		
		$query = $db
			-> getQuery(true)
			-> select("p.jaar							AS jaar")
			-> select("s.naam							AS plaats")
			-> select("c.id								AS kamp_id")
			-> select("c.naam							AS kamp")
			-> select("c.deelnamekosten					AS deelnemersbijdrage")
			-> select("c.minimumAantalDeelnemers		AS minimumAantalDeelnemers")
			-> select("c.maximumAantalDeelnemers		AS maximumAantalDeelnemers")
			-> from('#__kampinfo_hitproject p')
			-> join('LEFT', '#__kampinfo_hitsite AS s ON s.hitproject_id=p.id')
			-> join('LEFT', '#__kampinfo_hitcamp AS c ON c.hitsite_id=s.id')
			-> where('(p.id = ' . (int) ($db->escape($projectId)) . ')')
			-> order('p.jaar, s.naam, c.naam');
		
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		return $rows;
	}

}
