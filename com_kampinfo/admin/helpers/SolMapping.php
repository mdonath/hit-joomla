<?php
class SolMapping {

	/**
	 * Geeft de mapping voor de inschrijf aantallen per kamp voor het opgegeven jaar.
	 * 
	 * @param unknown $jaar
	 * @return multitype:IgnoredVeld GewoonVeld
	 */
	public static function getInschrijvingenMapping($jaar, $type="normal") {
		if ($type == "json") {
			// 2019: Iemand vond het handig om enkele velden ineens lowercase te maken...
			// 2020: En dit jaar heet 'Formulier' ineens 'Formuliernaam'...
			$mapping = array(
				'Locatie' => new IgnoredVeld()
				, 'formuliernummer' => new GewoonVeld('shantiFormuliernummer')
				, 'Formuliernaam' => new GewoonVeld('formulierNaam')
				, 'Aantal dln\'s' => new GewoonVeld('aantalDeelnemers')
				, 'minimum leeftijd' => new GewoonVeld('minimumLeeftijd')
				, 'maximum leeftijd' => new GewoonVeld('maximumLeeftijd')
				, 'minimum aantal deelnemers' => new GewoonVeld('minimumAantalDeelnemers')
				, 'maximum aantal deelnemers' => new GewoonVeld('maximumAantalDeelnemers')
				, 'Gereserveerd' => new GewoonVeld('gereserveerd')
				, 'Subgroepen' => new GewoonVeld('aantalSubgroepen')
				, 'subgroepcategorie' => new GewoonVeld('subgroepcategorie')
			);
		} else {
			$mapping = array(
				'Locatie' => new IgnoredVeld()
				, 'Formuliernummer' => new GewoonVeld('shantiFormuliernummer')
				, 'Formulier' => new GewoonVeld('formulierNaam')
				, 'Aantal dln\'s' => new GewoonVeld('aantalDeelnemers')
				, 'Minimum leeftijd' => new GewoonVeld('minimumLeeftijd')
				, 'Maximum leeftijd' => new GewoonVeld('maximumLeeftijd')
				, 'Minimum aantal deelnemers' => new GewoonVeld('minimumAantalDeelnemers')
				, 'Maximum aantal deelnemers' => new GewoonVeld('maximumAantalDeelnemers')
				, 'Gereserveerd' => new GewoonVeld('gereserveerd')
				, 'Subgroepen' => new GewoonVeld('aantalSubgroepen')
				, 'Subgroepcategorie' => new GewoonVeld('subgroepcategorie')
			);
		}
		return $mapping;
	}
	
	/**
	 * Geeft de mapping voor de deelnemersgegevens voor het opgegeven jaar.
	 * 
	 * @param unknown $jaar
	 * @return multitype:GewoonVeld LeeftijdVeld GeslachtVeld DatumVeld
	 */
	public static function getDeelnemergegevensMapping($jaar, $type="normal") {
		if ($type == 'json') {
			$mapping = array(
				'dln.nr.' => new GewoonVeld('dlnnr')
				, 'lid plaats' => new GewoonVeld('plaats')
				, 'Land' => new GewoonVeld('land')
				, 'Lid leeftijd' => new GewoonVeld('leeftijd')
				, 'lid geslacht' => new GeslachtVeld('geslacht', 'M', 'F')
				, 'datum inschrijving' => new DatumVeld('datumInschrijving', 'Y-m-d')
				, 'formulier' => new GewoonVeld('formulier')
				, 'deelnemer status' => new GewoonVeld('status')
			);
		} else {
			$mapping = array(
				'Dln.nr.' => new GewoonVeld('dlnnr')
				, 'Lid plaats' => new GewoonVeld('plaats')
				, 'Land' => new GewoonVeld('land')
				, 'Lid geboortedatum' => new LeeftijdVeld('leeftijd', $jaar)
				, 'Lid geslacht' => new GeslachtVeld('geslacht')
				, 'Datum inschrijving' => new DatumVeld('datumInschrijving')
				, 'Formulier' => new GewoonVeld('formulier')
				, 'Deelnemer status' => new GewoonVeld('status')
			);
		}
		return $mapping;
	}
}