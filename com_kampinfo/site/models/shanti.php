<?php defined('_JEXEC') or die('Restricted access');

include_once dirname(__FILE__) . '/kampinfomodelparent.php';

/**
 * KampInfo Shanti Model.
 */
class KampInfoModelShanti extends KampInfoModelParent {

	public function getShantiData() {
		$params =JComponentHelper::getParams('com_kampinfo');
		$projectId = $params->get('huidigeActieveJaar');
		return $this->getData($projectId);
	}
	
	//////////////////////////////////////
	
	private function getData($projectId) {
		$db = JFactory::getDBO();
		
		$query = $db
			-> getQuery(true)
			-> select("p.jaar									AS kampinfo_project_jaar")
			-> select("c.id										AS kampinfo_camp_id")
			-> select("p.shantiEvenementId						AS kampinfo_project_evenement_id")
			-> select("p.inschrijvingKosteloosAnnulerenDatum	AS frm_cancel_dt1")
			-> select("p.inschrijvingGeenRestitutieDatum		AS frm_cancel_dt2")
			-> select("p.inningsdatum							AS kampinfo_project_inningsdatum")
			-> select("s.naam									AS frm_location_nm")
			-> select("s.projectcode							AS projectcode")
			-> select("c.naam									AS frm_nm")
			-> select("COALESCE(c.isouderkind,0)				AS kampinfo_camp_isouder")
			-> select("c.deelnamekosten							AS frm_price")
			-> select("c.startDatumTijd							AS frm_from_dt")
			-> select("c.eindDatumTijd							AS frm_till_dt")
			-> select("p.inschrijvingStartdatum					AS frm_book_from_dt")
			-> select("p.inschrijvingEinddatum					AS frm_book_till_dt")
			-> select("c.minimumLeeftijd						AS frm_min_age")
			-> select("c.maximumLeeftijd						AS frm_max_age")
			-> select("c.margeAantalDagenTeJong					AS frm_min_age_margin_days")
			-> select("c.margeAantalDagenTeOud					AS frm_max_age_margin_days")
			-> select("c.minimumAantalDeelnemers				AS frm_part_min_ct")
			-> select("c.maximumAantalDeelnemers				AS frm_part_max_ct")
			-> select("c.maximumAantalUitEenGroep				AS frm_max_outof_group")
			-> select("c.minimumAantalSubgroepjes				AS fte_teams_min_ct")
			-> select("c.maximumAantalSubgroepjes				AS fte_teams_max_ct")
			-> select("c.subgroepsamenstellingMinimum			AS fte_parts_min_ct")
			-> select("c.subgroepsamenstellingMaximum			AS fte_parts_max_ct")
			-> select("c.subgroepsamenstellingExtra				AS fte_modulo")
			-> from('#__kampinfo_hitproject p')
			-> join('LEFT', '#__kampinfo_hitsite AS s ON s.hitproject_id=p.id')
			-> join('LEFT', '#__kampinfo_hitcamp AS c ON c.hitsite_id=s.id')
			-> where('(p.id = ' . (int) ($db->escape($projectId)) . ')')
			-> where('c.published = 1')
			-> order('p.jaar, s.naam, c.naam');
		
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		$datumVelden = ['frm_cancel_dt1', 'frm_cancel_dt2', 'kampinfo_project_inningsdatum', 'frm_from_dt', 'frm_till_dt', 'frm_book_from_dt', 'frm_book_till_dt'];
		$tijdVelden = ['frm_from_dt', 'frm_till_dt'];
		foreach ($rows as $row) {
			$this->convertDateFromUTC($row, $datumVelden);
			$this->splitDate($row, $datumVelden);
			$this->splitTime($row, $tijdVelden);
			$this->removeDateFields($row, $datumVelden);
		}
		return $rows;
	}

	private function convertDateFromUTC($object, $datumVelden) {
		$UTC = new DateTimeZone("UTC");
		$tz = new DateTimeZone("Europe/Amsterdam");
		foreach ($datumVelden as $veld) {
			$localDate = DateTime::createFromFormat('Y-m-d H:i:s', $object->$veld, $UTC);
			$localDate->setTimezone($tz);
			$object->$veld = $localDate;
		}
	}
	
	private function splitDate($object, $datumVelden) {
		foreach ($datumVelden as $veld) {
			$localDate = $object->$veld;
			
			$dag = $veld . '_day';
			$object->$dag = $localDate->format('d');
			$maand = $veld . '_month';
			$object->$maand = $localDate->format('m');
			$jaar = $veld . '_year';
			$object->$jaar = $localDate->format('Y');
		}
	}

	private function splitTime($object, $datumVelden) {
		foreach ($datumVelden as $veld) {
			$localDate = $object->$veld;

			$tijd = substr($veld, 0, -3) . '_time';
			$object->$tijd = $localDate->format('H:i');
		}
	}
	
	private function removeDateFields($object, $datumVelden) {
		foreach ($datumVelden as $veld) {
			unset($object->$veld);
		}
	}
	
}
