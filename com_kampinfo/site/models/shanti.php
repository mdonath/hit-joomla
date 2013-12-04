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
		
		$query = $db->getQuery(true);
		$query->select("p.jaar							AS kampinfo_project_jaar");
		$query->select("c.id							AS kampinfo_camp_id");
		$query->select("p.shantiEvenementId				AS kampinfo_project_evenement_id");
		$query->select("dayofmonth(p.inschrijvingKosteloosAnnulerenDatum)	AS frm_cancel_dt1_day");
		$query->select("month(p.inschrijvingKosteloosAnnulerenDatum)		AS frm_cancel_dt1_month");
		$query->select("year(p.inschrijvingKosteloosAnnulerenDatum)			AS frm_cancel_dt1_year");
		$query->select("dayofmonth(p.inschrijvingGeenRestitutieDatum)		AS frm_cancel_dt2_day");
		$query->select("month(p.inschrijvingGeenRestitutieDatum)			AS frm_cancel_dt2_month");
		$query->select("year(p.inschrijvingGeenRestitutieDatum)				AS frm_cancel_dt2_year");
		$query->select("dayofmonth(p.inningsdatum)							AS kampinfo_project_inningsdatum_day");
		$query->select("month(p.inningsdatum)								AS kampinfo_project_inningsdatum_month");
		$query->select("year(p.inningsdatum)								AS kampinfo_project_inningsdatum_year");
		$query->select("s.naam							AS frm_location_nm");
		$query->select("case s.naam when 'Alphen' then 83 when 'Baarn' then 86 when 'Dwingeloo' then 84 when 'Harderwijk' then 85 when 'Mook' then 88 when 'Zeeland' then 87 end AS fld_led_id");
		$query->select("c.naam							AS frm_nm");
		$query->select("c.deelnamekosten				AS frm_price");
		$query->select("dayofmonth(c.startDatumTijd)	 		AS frm_from_dt_day");
		$query->select("month(c.startDatumTijd)					AS frm_from_dt_month");
		$query->select("year(c.startDatumTijd)					AS frm_from_dt_year");
		$query->select("date_format(c.startDatumTijd, '%H:%i')	AS frm_from_time");
		$query->select("dayofmonth(c.eindDatumTijd)				AS frm_till_dt_day");
		$query->select("month(c.eindDatumTijd)					AS frm_till_dt_month");
		$query->select("year(c.eindDatumTijd)					AS frm_till_dt_year");
		$query->select("date_format(c.eindDatumTijd, '%H:%i')	AS frm_till_time");
		$query->select("dayofmonth(p.inschrijvingStartdatum)	AS frm_book_from_dt_day");
		$query->select("month(p.inschrijvingStartdatum) 		AS frm_book_from_dt_month");
		$query->select("year(p.inschrijvingStartdatum)			AS frm_book_from_dt_year");
		$query->select("dayofmonth(p.inschrijvingEinddatum)	AS frm_book_till_dt_day");
		$query->select("month(p.inschrijvingEinddatum)		AS frm_book_till_dt_month");
		$query->select("year(p.inschrijvingEinddatum)		AS frm_book_till_dt_year");
		$query->select("c.minimumLeeftijd					AS frm_min_age");
		$query->select("c.maximumLeeftijd					AS frm_max_age");
		$query->select("c.margeAantalDagenTeJong			AS frm_min_age_margin_days");
		$query->select("c.margeAantalDagenTeOud				AS frm_max_age_margin_days");
		$query->select("c.minimumAantalDeelnemers			AS frm_part_min_ct");
		$query->select("c.maximumAantalDeelnemers			AS frm_part_max_ct");
		$query->select("c.maximumAantalUitEenGroep			AS frm_max_outof_group");
		$query->select("c.minimumAantalSubgroepjes			AS fte_teams_min_ct");
		$query->select("c.maximumAantalSubgroepjes			AS fte_teams_max_ct");
		$query->select("c.subgroepsamenstellingMinimum		AS fte_parts_min_ct");
		$query->select("c.subgroepsamenstellingMaximum		AS fte_parts_max_ct");
		$query->select("c.subgroepsamenstellingExtra		AS fte_modulo");
		
		$query->from('#__kampinfo_hitproject p');
		$query->join('LEFT', '#__kampinfo_hitsite AS s ON s.hitproject_id=p.id');
		$query->join('LEFT', '#__kampinfo_hitcamp AS c ON c.hitsite_id=s.id');
		$query->where('(p.id = ' . (int) ($db->getEscaped($projectId)) . ')');
		$query->order('p.jaar, s.naam, c.naam');
		
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		return $rows;
	}

	
}
