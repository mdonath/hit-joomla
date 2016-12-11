<?php defined('_JEXEC') or die('Restricted access');

include_once dirname(__FILE__) . '/kampinfomodelparent.php';

function sort_finlist($a, $b) {
	$result = strcmp($a->plaats, $b->plaats);
	if ($result == 0) {
		$result = $a->budgetnummer - $b->budgetnummer;
		if ($result == 0) {
			$result = strcmp($a->kamp, $b->kamp);
		}
	}
	return $result;
}
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

		$plaatsQuery = $db
			-> getQuery(true)
			-> select("p.jaar								AS jaar")
			-> select("s.naam								AS plaats")
			-> select("s.id									AS kamp_id")
			-> select("concat(s.afkorting,'-c')				AS afko")
			-> select("s.budgetnummer + 900					AS budgetnummer")
			-> select("'C-team'								AS kamp")
			-> select("0									AS deelnemersbijdrage")
			-> select("0									AS minimumAantalDeelnemers")
			-> select("0									AS maximumAantalDeelnemers")
			-> select("s.aantalMedewerkers					AS aantalMedewerkers")
			-> from('#__kampinfo_hitproject p')
			-> join('LEFT', '#__kampinfo_hitsite AS s ON s.hitproject_id=p.id')
			-> where('(p.id = ' . (int) ($db->escape($projectId)) . ')')
			;
		$query = $db
			-> getQuery(true)
			-> select("p.jaar								AS jaar")
			-> select("s.naam								AS plaats")
			-> select("c.id									AS kamp_id")
			-> select("concat(s.afkorting,'-',c.afkorting)	AS afko")
			-> select("c.budgetnummer						AS budgetnummer")
			-> select("c.naam								AS kamp")
			-> select("c.deelnamekosten						AS deelnemersbijdrage")
			-> select("c.minimumAantalDeelnemers			AS minimumAantalDeelnemers")
			-> select("c.maximumAantalDeelnemers			AS maximumAantalDeelnemers")
			-> select("c.aantalMedewerkers					AS aantalMedewerkers")
			-> from('#__kampinfo_hitproject p')
			-> join('LEFT', '#__kampinfo_hitsite AS s ON s.hitproject_id=p.id')
			-> join('LEFT', '#__kampinfo_hitcamp AS c ON c.hitsite_id=s.id')
			-> where('(p.id = ' . (int) ($db->escape($projectId)) . ')')
			-> union($plaatsQuery)
		;
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		usort($rows, "sort_finlist");
		
		return $rows;
	}

}
