<?php
class SolMapping {
	
	/**
	 * Geeft de mapping voor de kampgegevens voor het opgegeven jaar.
	 * 
	 * @param unknown $jaar
	 * @return multitype:GewoonVeld ActiviteitengebiedVeld DatumVeld TijdVeld IcoonVeld DoelgroepVeld
	 */
	public static function getKampgegevensMapping($jaar) {
		$mapping = array(
				// 'd eelnemersnummer'												=> new GewoonVeld('d eelnemersnummer')
				'HIT-Kamp in HIT-Plaats'											=> new GewoonVeld('plaatsNaam') // icm jaar -> hitsite
				, 'HIT-Kamp naam'													=> new GewoonVeld('naam')
				//, 'HIT-Kamp Contactpersoon voor helpdesk'							=> new IgnoredVeld()
				//, 'HIT-Kamp Contactpersoon Emailadres voor Helpdesk'				=> new IgnoredVeld()
				//, 'HIT-Kamp Contactpersoon Telefoonnummer voor Helpdesk'			=> new IgnoredVeld()
				//, 'HIT-Kamp doelstelling'											=> new IgnoredVeld()
				, 'HIT-Kamp Activiteitengebieden: Uitdagende Scoutingtechnieken'	=> new ActiviteitengebiedVeld('uitdagend')
				, 'HIT-Kamp Activiteitengebieden: Expressie'						=> new ActiviteitengebiedVeld('expressie')
				, 'HIT-Kamp Activiteitengebieden: Sport en Spel'					=> new ActiviteitengebiedVeld('sportenspel')
				, 'HIT-Kamp Activiteitengebieden: Buitenleven'						=> new ActiviteitengebiedVeld('buitenleven')
				, 'HIT-Kamp Activiteitengebieden: Identiteit'						=> new ActiviteitengebiedVeld('identiteit')
				, 'HIT-Kamp Activiteitengebieden: Internationaal'					=> new ActiviteitengebiedVeld('internationaal')
				, 'HIT-Kamp Activiteitengebieden: Samenleving'						=> new ActiviteitengebiedVeld('samenleving')
				, 'HIT-Kamp Activiteitengebieden: Veilig en Gezond'					=> new ActiviteitengebiedVeld('veiligengezond')
				, 'HIT-Kamp titeltekst'												=> new GewoonVeld('titeltekst')
				//, 'HIT-Kamp Couranttekst'											=> new GewoonVeld('courantTekst')
				, 'HIT-Kamp Startdatum'												=> new DatumVeld('startDatumTijd')
				, 'HIT-Kamp Starttijd'												=> new TijdVeld('startDatumTijd')
				, 'HIT-Kamp Einddatum'												=> new DatumVeld('eindDatumTijd')
				, 'HIT-Kamp Eindtijd'												=> new TijdVeld('eindDatumTijd')
				, 'Deelnamekosten'													=> new GewoonVeld('deelnamekosten')
				, 'Leeftijd minimaal'												=> new GewoonVeld('minimumLeeftijd')
				, 'Leeftijd maximaal'												=> new GewoonVeld('maximumLeeftijd')
				, 'Subgroepsamenstelling van'										=> new GewoonVeld('subgroepsamenstellingMinimum')
				, 'Subgroepsamenstelling tot en met'								=> new GewoonVeld('subgroepsamenstellingMaximum')
				, 'Subgroep extra'													=> new GewoonVeld('subgroepsamenstellingExtra')
				, 'De HIT Icoontjes: Staand kamp'											=> new IcoonVeld('staand')
				, 'De HIT Icoontjes: Trekken per fiets'										=> new IcoonVeld('fiets')
				, 'De HIT Icoontjes: Trekken met rugzak'									=> new IcoonVeld('hike')
				, 'De HIT Icoontjes: Trekken per kano'										=> new IcoonVeld('kano')
				, 'De HIT Icoontjes: Trekkend per boot'										=> new IcoonVeld('zeilboot')
				, 'De HIT Icoontjes: Lopen zonder rugzak'									=> new IcoonVeld('geenrugz')
				, 'De HIT Icoontjes: Lopen met een ander voorwerp'							=> new IcoonVeld('hikevr')
				, 'De HIT Icoontjes: Inschrijven per persoon'								=> new IcoonVeld('0pers')
				, 'De HIT Icoontjes: Inschrijven per groep'									=> new IcoonVeld('groepje')
				, 'De HIT Icoontjes: Overnachten in een zelfmeegenomen tent'				=> new IcoonVeld('tent')
				, 'De HIT Icoontjes: Overnachten in een frietbuil'							=> new IcoonVeld('friet')
				, 'De HIT Icoontjes: Overnachten zonder tent'								=> new IcoonVeld('nacht')
				, 'De HIT Icoontjes: Overnachten in tenten verzorgd door staf'				=> new IcoonVeld('tent_opgezet')
				, 'De HIT Icoontjes: Overnachten in gebouw'									=> new IcoonVeld('gebouw')
				, 'De HIT Icoontjes: Totale afstand is 0 km'								=> new IcoonVeld('0km')
				, 'De HIT Icoontjes: Totale afstand is 5 km'								=> new IcoonVeld('5km')
				, 'De HIT Icoontjes: Totale afstand is 15 km'								=> new IcoonVeld('15km')
				, 'De HIT Icoontjes: Totale afstand is 20 km'								=> new IcoonVeld('20km')
				, 'De HIT Icoontjes: Totale afstand is 25 km'								=> new IcoonVeld('25km')
				, 'De HIT Icoontjes: Totale afstand is 30 km'								=> new IcoonVeld('30km')
				, 'De HIT Icoontjes: Totale afstand is 35 km'								=> new IcoonVeld('35km')
				, 'De HIT Icoontjes: Totale afstand is 40 km'								=> new IcoonVeld('40km')
				, 'De HIT Icoontjes: Totale afstand is 45 km'								=> new IcoonVeld('45km')
				, 'De HIT Icoontjes: Totale afstand is 50 km'								=> new IcoonVeld('50km')
				, 'De HIT Icoontjes: Totale afstand is 55 km'								=> new IcoonVeld('55km')
				, 'De HIT Icoontjes: Totale afstand is 60 km'								=> new IcoonVeld('60km')
				, 'De HIT Icoontjes: Totale afstand is 65 km'								=> new IcoonVeld('65km')
				, 'De HIT Icoontjes: Totale afstand is 70 km'								=> new IcoonVeld('70km')
				, 'De HIT Icoontjes: Totale afstand is 75 km'								=> new IcoonVeld('75km')
				, 'De HIT Icoontjes: Totale afstand is 80 km'								=> new IcoonVeld('80km')
				, 'De HIT Icoontjes: Totale afstand is 85 km'								=> new IcoonVeld('85km')
				, 'De HIT Icoontjes: Totale afstand is 90 km'								=> new IcoonVeld('90km')
				, 'De HIT Icoontjes: Totale afstand is 100 km'								=> new IcoonVeld('100km')
				, 'De HIT Icoontjes: Totale afstand is 120 km'								=> new IcoonVeld('120km')
				, 'De HIT Icoontjes: Totale afstand is 150 km'								=> new IcoonVeld('150km')
				, 'De HIT Icoontjes: Koken op houtvuur zonder pannen'						=> new IcoonVeld('vuur')
				, 'De HIT Icoontjes: Koken op houtvuur met pannen'							=> new IcoonVeld('opvuur')
				, 'De HIT Icoontjes: Koken op gas met pannen'								=> new IcoonVeld('gas')
				, 'De HIT Icoontjes: Gekookt door de staf'									=> new IcoonVeld('stafkookt')
				, 'De HIT Icoontjes: Kennis van kaart en kompas op eenvoudig niveau'		=> new IcoonVeld('k_ks')
				, 'De HIT Icoontjes: Kennis van kaart en kompas op gevorderd niveau'		=> new IcoonVeld('k_kv')
				, 'De HIT Icoontjes: Kennis van kaart en kompas op specialistisch nivea'	=> new IcoonVeld('k_kgv')
				, 'De HIT Icoontjes: Activiteit waarmee een insigne kan worden behaald'		=> new IcoonVeld('insigne')
				, 'De HIT Icoontjes: Zwemdiploma verplicht'									=> new IcoonVeld('zwem')
				, 'De HIT Icoontjes: Mobieltje meenemen'									=> new IcoonVeld('mobieltje')
				, 'De HIT Icoontjes: Mobieltjes zijn verboden'								=> new IcoonVeld('geenmobieltje')
				, 'De HIT Icoontjes: Geschikt voor minder validen (rolstoel)'				=> new IcoonVeld('rolstoel')
				, 'De HIT Icoontjes: Vraagteken Mysterie elementen'							=> new IcoonVeld('vraagt')
				, 'De HIT Icoontjes: Buitenland - ID kaart of paspoort verplicht'			=> new IcoonVeld('buitenland')
				, 'De HIT Icoontjes: Trekkend per auto'										=> new IcoonVeld('auto')
				, 'HIT-Kamp websiteadres'											=> new GewoonVeld('websiteAdres')
				, 'HIT-Kamp websitetekst'											=> new GewoonVeld('websiteTekst')
				, 'Webadres naar foto1'												=> new GewoonVeld('webadresFoto1')
				, 'Webadres naar foto2'												=> new GewoonVeld('webadresFoto2')
				, 'Webadres naar foto3 of naar 1 Youtube filmpje'					=> new GewoonVeld('webadresFoto3')
				, 'HIT-Kamp contacttelefoonnummer voor website'						=> new GewoonVeld('websiteContactTelefoonnummer')
				, 'HIT-Kamp contactemailadres voor website  (HIT mailadres)'		=> new GewoonVeld('websiteContactEmailadres')
				, 'HIT-Kamp contactpersoon voor website'							=> new GewoonVeld('websiteContactpersoon')
				, 'Minimaal aantal deelnemers'										=> new GewoonVeld('minimumAantalDeelnemers')
				, 'Maximum aantal deelnemers'										=> new GewoonVeld('maximumAantalDeelnemers')
				, 'Maximum aantal subgroepjes'										=> new GewoonVeld('maximumAantalSubgroepjes')
				, 'Maximum aantal uit 1 Scoutinggroep'								=> new GewoonVeld('maximumAantalUitEenGroep')
				, 'Aantal dagen dat een deelnemer te jong mag zijn (standaard 90 dagen)'	=> new GewoonVeld('margeAantalDagenTeJong')
				, 'Aantal dagen dat een deelnemer te oud mag zijn (standaard 90 dagen)'		=> new GewoonVeld('margeAantalDagenTeOud')
				, 'Reden afwijking 90 dagen uitzondering'									=> new GewoonVeld('redenAfwijkingMarge')
				, 'Welke doelgroep mag alleen inschrijven voor deze HIT. (optioneel): Bevers 5-7 jaar'				=> new DoelgroepVeld('bevers')
				, 'Welke doelgroep mag alleen inschrijven voor deze HIT. (optioneel): Welpen 7-11 jaar'				=> new DoelgroepVeld('welpen')
				, 'Welke doelgroep mag alleen inschrijven voor deze HIT. (optioneel): Scouts 11-15 jaar'			=> new DoelgroepVeld('scouts')
				, 'Welke doelgroep mag alleen inschrijven voor deze HIT. (optioneel): Explorers 15-18 jaar'			=> new DoelgroepVeld('explorers')
				, 'Welke doelgroep mag alleen inschrijven voor deze HIT. (optioneel): Roverscouts 18 t/m 21 jaar'	=> new DoelgroepVeld('roverscouts')
				, 'Welke doelgroep mag alleen inschrijven voor deze HIT. (optioneel): Plusscouts 21+'				=> new DoelgroepVeld('plusscouts')
				, 'Welke doelgroep mag alleen inschrijven voor deze HIT. (optioneel): Volwassenen (ndlg)'			=> new DoelgroepVeld('ndlg')
				//, 'Mogen er deelnemers in een groepje te jong of te oud zijn.: Ja, deelnemers mogen 1 jaar te jong zijn,  max. aantal in een groepje:'			=> new IgnoredVeld()
				//, 'Mogen er deelnemers in een groepje te jong of te oud zijn. Reden: Ja, deelnemers mogen 1 jaar te jong zijn,  max. aantal in een groepje:'	=> new IgnoredVeld()
				//, 'Mogen er deelnemers in een groepje te jong of te oud zijn.: Ja, deelnemers mogen 1 jaar te oud zijn, max. aantal  in een groepje:'			=> new IgnoredVeld()
				//, 'Mogen er deelnemers in een groepje te jong of te oud zijn. Reden: Ja, deelnemers mogen 1 jaar te oud zijn, max. aantal  in een groepje:'		=> new IgnoredVeld()
				//, 'Mogen er deelnemers in een groepje te jong of te oud zijn.: Nee, geen leeftijdsuitzonderingen'												=> new IgnoredVeld()
				//, 'Overschrijding aantal deelnemers'								=> new GewoonVeld('overschrijdingAantalDeelnemers')
				//, 'Opmerkingen voor Helpdesk:'										=> new IgnoredVeld()
				, 'Akkoord HIT-kamp'												=> new GewoonVeld('akkoordHitKamp')
				, 'Akkoord HIT-plaats'												=> new GewoonVeld('akkoordHitPlaats')
				, 'Shantiformuliernummer'											=> new GewoonVeld('shantiFormuliernummer')
		);
		return $mapping;
	}

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