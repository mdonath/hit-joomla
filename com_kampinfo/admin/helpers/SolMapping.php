<?php
class SolMapping {

	/**
	 * Geeft de mapping voor de inschrijf aantallen per kamp voor het opgegeven jaar.
	 * 
	 * @param unknown $jaar
	 * @return multitype:IgnoredVeld GewoonVeld
	 */
	public static function getInschrijvingenMapping($jaar) {
		$mapping = array(
				'Locatie' => new IgnoredVeld()
				, 'Formuliernummer' => new GewoonVeld('shantiFormuliernummer')
				, 'Aantal dln\'s' => new GewoonVeld('aantalDeelnemers')
				, 'Gereserveerd' => new GewoonVeld('gereserveerd')
				, 'Subgroepen' => new GewoonVeld('aantalSubgroepen')
		);
		return $mapping;
	}
	
	/**
	 * Geeft de mapping voor de deelnemersgegevens voor het opgegeven jaar.
	 * 
	 * @param unknown $jaar
	 * @return multitype:GewoonVeld LeeftijdVeld GeslachtVeld DatumVeld
	 */
	public static function getDeelnemergegevensMapping($jaar) {
		$mapping = array(
				'Dln.nr.' => new GewoonVeld('dlnnr')
	 		, 'Lid plaats' => new GewoonVeld('plaats')
	 		, 'Land' => new GewoonVeld('land')
	 		, 'Lid geboortedatum' => new LeeftijdVeld('leeftijd', $jaar)
	 		, 'Lid geslacht' => new GeslachtVeld('geslacht')
	 		, 'Datum inschrijving' => new DatumVeld('datumInschrijving')
	 		, 'Formulier' => new GewoonVeld('formulier')
		);
		return $mapping;
	}
}