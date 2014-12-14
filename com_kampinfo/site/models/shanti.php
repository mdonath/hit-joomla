<?php defined('_JEXEC') or die('Restricted access');

include_once dirname(__FILE__) . '/kampinfomodelparent.php';

/**
 * KampInfo Shanti Model.
 */
class KampInfoModelShanti extends KampInfoModelParent {

	public function getShantiData() {
		$params = &JComponentHelper::getParams('com_kampinfo');
		$projectId = $params->get('huidigeActieveJaar');
		return $this->getData($projectId);
	}
	
	//////////////////////////////////////
	
	private function getData($projectId) {
		$db = JFactory :: getDBO();
		
		$query = $db
			-> getQuery(true)
			-> select("p.jaar							AS kampinfo_project_jaar")
			-> select("c.id								AS kampinfo_camp_id")
			-> select("p.shantiEvenementId				AS kampinfo_project_evenement_id")
			-> select("dayofmonth(p.inschrijvingKosteloosAnnulerenDatum)	AS frm_cancel_dt1_day")
			-> select("month(p.inschrijvingKosteloosAnnulerenDatum)			AS frm_cancel_dt1_month")
			-> select("year(p.inschrijvingKosteloosAnnulerenDatum)			AS frm_cancel_dt1_year")
			-> select("dayofmonth(p.inschrijvingGeenRestitutieDatum)		AS frm_cancel_dt2_day")
			-> select("month(p.inschrijvingGeenRestitutieDatum)				AS frm_cancel_dt2_month")
			-> select("year(p.inschrijvingGeenRestitutieDatum)				AS frm_cancel_dt2_year")
			-> select("dayofmonth(p.inningsdatum)							AS kampinfo_project_inningsdatum_day")
			-> select("month(p.inningsdatum)								AS kampinfo_project_inningsdatum_month")
			-> select("year(p.inningsdatum)									AS kampinfo_project_inningsdatum_year")
			-> select("s.naam									AS frm_location_nm")
			-> select("s.projectcode							AS projectcode")
			-> select("c.naam									AS frm_nm")
			-> select("COALESCE(c.isouderkind,0)				AS kampinfo_camp_isouder")
			-> select("c.deelnamekosten							AS frm_price")
			-> select("dayofmonth(c.startDatumTijd)	 			AS frm_from_dt_day")
			-> select("month(c.startDatumTijd)					AS frm_from_dt_month")
			-> select("year(c.startDatumTijd)					AS frm_from_dt_year")
			-> select("date_format(c.startDatumTijd, '%H:%i')	AS frm_from_time")
			-> select("dayofmonth(c.eindDatumTijd)				AS frm_till_dt_day")
			-> select("month(c.eindDatumTijd)					AS frm_till_dt_month")
			-> select("year(c.eindDatumTijd)					AS frm_till_dt_year")
			-> select("date_format(c.eindDatumTijd, '%H:%i')	AS frm_till_time")
			-> select("dayofmonth(p.inschrijvingStartdatum)		AS frm_book_from_dt_day")
			-> select("month(p.inschrijvingStartdatum) 			AS frm_book_from_dt_month")
			-> select("year(p.inschrijvingStartdatum)			AS frm_book_from_dt_year")
			-> select("dayofmonth(p.inschrijvingEinddatum)		AS frm_book_till_dt_day")
			-> select("month(p.inschrijvingEinddatum)			AS frm_book_till_dt_month")
			-> select("year(p.inschrijvingEinddatum)			AS frm_book_till_dt_year")
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
			-> where('(p.id = ' . (int) ($db->getEscaped($projectId)) . ')')
			-> where('c.published = 1')
			-> order('p.jaar, s.naam, c.naam');
		
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		return $rows;
	}
}
