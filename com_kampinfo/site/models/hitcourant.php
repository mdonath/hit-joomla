<?php defined('_JEXEC') or die('Restricted access');

include_once dirname(__FILE__) . '/kampinfomodelparent.php';

/**
 * KampInfo HitCourant Model.
 */
class KampInfoModelHitCourant extends KampInfoModelParent {

	public function generate() {
		$params = &JComponentHelper::getParams('com_kampinfo');

		if ($this->magHet($params)) {
			$projectId = $params->get('huidigeActieveJaar');
			$this->hitcourant($this->getHitData($projectId));
		} else {
			echo "What's the magic word?";
		}
	}

	//////////////////////////////////////
	function getHitPlaatsen($projectId) {
		$db = JFactory :: getDBO();
	
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__kampinfo_hitsite s');
		$query->where('(s.hitproject_id = ' . (int) ($db->getEscaped($projectId)) . ')');
		$query->order('s.naam');
	
		$db->setQuery($query);
		$plaatsenInJaar = $db->loadObjectList();
		return $plaatsenInJaar;
	}
	function getHitKampen($hitsiteId, $iconenLijst) {
		$db = JFactory :: getDBO();
	
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__kampinfo_hitcamp c');
		$query->where('(c.hitsite_id = ' . (int)($db->getEscaped($hitsiteId)) . ')');
		$query->order('c.naam');
	
		$db->setQuery($query);
		$kampenInPlaats = $db->loadObjectList();
	
		foreach ($kampenInPlaats as $kamp) {
			$kamp->icoontjes = $this->explodeIcoontjes($kamp, $iconenLijst);
		}
		return $kampenInPlaats;
	}
	
	//////////////////////////////////////
	private function getHitData($projectId) {
		$hit = $this->getHitProject($projectId);
		$this->prepareProject($hit);

		// plaatsen
		$plaatsen = $this->getHitPlaatsen($projectId);
		$this->preparePlaatsen($hit, $plaatsen);
		
		// icoontjes
		$hit->gebruikteIconenVoorCourant = $this->getGebruikteIconen($hit);
				
		return $hit;
	}
	
	private function getGebruikteIconen($hit) {
		$result = array();
		$afstand = false;
		foreach ($hit->hitPlaatsen as $plaats) {
			foreach($plaats->hitKampen as $kamp) {
				foreach ($kamp->icoontjes as $icon) {
					if ($icon->soort != 'A') { 
						$result[$icon->volgorde] = $icon;
					} else {
						if (!$afstand and $icon->naam != '0km') {
							$afstand = true;
							$result[$icon->volgorde] = $icon;
						}
					}
				}
			}
		}
		ksort($result);
		return array_values($result);
	}
	
	private function prepareProject($project) {
		$this->convertDate($project, array('vrijdag', 'maandag', 'inschrijvingStartdatum', 'inschrijvingEinddatum'));
		$project->heeftBeginEnEindInVerschillendeMaanden = $project->vrijdag->format('m') != $project->maandag->format('m');
	}

	private function convertDate($object, $datumVelden) {
		foreach ($datumVelden as $veld) {
			$object->$veld = DateTime::createFromFormat('Y-m-d', $object->$veld);
		}
	}

	private function convertDateTime($object, $datumVelden) {
		foreach ($datumVelden as $veld) {
			$object->$veld = DateTime::createFromFormat('Y-m-d H:i:s', $object->$veld);
		}
	}

	private function preparePlaatsen($project, $plaatsen) {
		$project->hitPlaatsen = $plaatsen;
		$iconenLijst = array();
		foreach ($plaatsen as $plaats) {
			$kampen = $this->getHitKampen($plaats->id, $iconenLijst);
			$plaats->hitKampen = $kampen;
			foreach ($kampen as $kamp) {
				$this->prepareKamp($kamp);
			}
		}
	}

	private function prepareKamp($kamp) {
		$this->convertDateTime($kamp, array('startDatumTijd', 'eindDatumTijd'));
		$kamp->heeftBeginEnEindInVerschillendeMaanden = $kamp->startDatumTijd->format('m') != $kamp->eindDatumTijd->format('m');
	}

	private function magHet($params) {
		return true;
		$configuredSecret = $params->get('downloadSecret');

		$jinput = JFactory::getApplication()->input;
		$givenSecret = $jinput->get('secret', '', 'string');
		return $configuredSecret == $givenSecret;
	}

	/**
	 * 
	 * @param unknown $hit
	 */
	private function hitcourant($hit) {
		echo <<< EOT
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
	        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	        <meta name="author" content="Martijn Donath" />
	</head>

	<body>
		{$this->hitcourant_intro($hit)}
		{$this->hitcourant_iconenoverzicht($hit)}
		{$this->hitcourant_krant($hit)}
		{$this->hitcourant_colofon($hit)}
	</body>
</html>
EOT;
	}

	/**
	 * Toont de eerste bladzijde van de courant, met intro, datums en icoontjesoverzicht.
	 * 
	 * @param unknown $hit
	 */
	private function hitcourant_intro($hit) {
		$count='count';
		echo <<< EOT
	<h1 style="text-align: center; font-family: Impact; color: #005eb0; margin-top: .75em; margin-bottom: 0em; font-weight: normal;">(hier thema $hit->jaar)</h1>
	<h2 style="font-family: Helvetica">HIT $hit->jaar Jouw paasweekend vol Scoutinguitdaging!</h2>
	<p style="font-family: Helvetica"><strong>Wat ga jij doen met Pasen $hit->jaar? Naar de HIT natuurlijk! Want
	in $hit->jaar kun je bij de HIT kiezen uit meer dan 60 totaal verschillende, spannende en
	uitdagende activiteiten op {$count($hit->hitPlaatsen)} plaatsen in Nederland, of zelfs in het
	buitenland!</strong></p>
	
	<p style="font-family: Helvetica">De HIT staat voor Hikes, Interessekampen en Trappersexpedities en wordt elk jaar gehouden
	tijdens de paasdagen. Een paar duizend scouts tussen de 7 en de 88 jaar beleven een ultieme
	Scoutingactiviteit waarin je alles kunt tegenkomen wat Scouting te bieden heeft.
	
	<p style="font-family: Helvetica">In $hit->jaar vindt de HIT plaats tussen 
EOT;
	
	if ($hit->heeftBeginEnEindInVerschillendeMaanden) {
		echo ($this->format($hit->vrijdag, "l j F"));
	} else {
		echo ($this->format($hit->vrijdag, "l j"));
	}
	
echo <<< EOT
	en {$this->format($hit->maandag, "l j F")}.
	Vanaf {$this->format($hit->inschrijvingStartdatum, "j F")} kun je je inschrijven. Lees snel verder in deze HIT-courant of kijk op de website welke te gekke HIT voor jou en je
	Scoutingvrienden er dit jaar weer bij zit! Schrijf je snel in want elke HIT heeft maar een beperkt aantal plaatsen.</p>
	
	<div style="font-family: Helvetica; border: .2em solid black; padding: 1em;">
	<h1  style="font-family: Impact; color: #005eb0; margin-top: .75em; margin-bottom: 0em; font-weight: normal; text-align: center; margin-top: 0em;">Hoe kan ik me inschrijven?</h1>
	<p>Heb je je keuze gemaakt, of wil je eerst nog meer weten?
	Ga dan naar de HIT website op <u>hit.scouting.nl</u> voor meer informatie over de door jou gekozen HIT.
	Vanaf daar vind je onderaan ook meteen een link naar het inschrijfformulier.
	Log hiervoor wel even in op de website van Scouting Nederland.<br />
	Kom je er niet meteen uit? Op de website staat een uitgebreide handleiding. Ook kun je via de
	website contact opnemen met de HIT-helpdesk.<br />
	<div style="font-family: Helvetica; font-weight: bold; text-align: center; font-size: 120%;">
	Je kunt je inschrijven vanaf <span style="color: green;">{$this->format($hit->inschrijvingStartdatum, "j F Y")}</span>.
	De inschrijving sluit op <span style="color: red;">{$this->format($hit->inschrijvingEinddatum, "j F Y")}</span>!
	</div>
	</div>
	</font>
EOT;
	}
	
	private function hitcourant_iconenoverzicht($hit) {
echo <<< EOT
	<h2 style="text-align: center; color: green; font-style: italic; font-family: Helvetica">Verklaring van de symbolen in deze HIT-courant</h2>
	
	<table style="border-collapse: collapse; font-family: Helvetica;">
	{$this->print_iconen($hit->gebruikteIconenVoorCourant)}
	</table>
	
	<p style="text-align: center; font-family: Helvetica">Wijzigingen voorbehouden, kijk op de website bij de activiteit voor de laatste informatie</p>
EOT;

	}
	
	/**
	 * Print de gebruikte iconen.
	 * 
	 * @param unknown $iconen
	 * @return string
	 */
	private function print_iconen($iconen) {
		$result = '';
		$welkeKolomMethod = array('icoonLinkerKolom', 'icoonMiddelKolom', 'icoonRechterKolom');
		foreach($iconen as $index => $icoon) {
			$result .= $this->$welkeKolomMethod[$index%3]($icoon);
		}
		return $result;
	}
	
	/**
	 * Print de gegevens van alle HIT-Plaatsen.
	 * 
	 * @param unknown $hit
	 */
	private function hitcourant_krant($hit) {
		foreach ($hit->hitPlaatsen as $plaats) {
			$this->hitcourant_plaats($plaats);
		}
	}
	
	/**
	 * Print de gegevens van een HIT-PLaats.
	 * 
	 * @param unknown $plaats
	 */
	private function hitcourant_plaats($plaats) {
		$strtolower = 'strtolower';
echo <<< EOT
	<p style="font-family: Helvetica">
	<img src="images/stories/hitlogo/hit_logo_h_web_{$strtolower($plaats->naam)}.png"><br/>
	Kijk op https://hit.scouting.nl/{$strtolower($plaats->naam)} voor meer info.<br/>
	{$plaats->hitCourantTekst}
	</p>
EOT;
		foreach ($plaats->hitKampen as $kamp) {
			$this->hitcourant_kamp($kamp);
		}
	}

	/**
	 * Print de gegevens van een kamp.
	 * 
	 * @param unknown $kamp
	 */
	private function hitcourant_kamp($kamp) {
echo <<< EOT
	<h1 style="font-family: Impact; color: #005eb0; margin-top: .75em; margin-bottom: 0em; font-weight: normal;">$kamp->naam</h1>
	<p style="text-align: right; margin-left: .1em">
EOT;
		foreach ($kamp->icoontjes as $icoon) {
			echo($this->icoonGroot($icoon));
		}
echo <<< EOT
	</p>
	<p style="text-align: right; font-weight: bold; font-family: Helvetica; font-weight: bold;">
EOT;
	if($kamp->heeftBeginEnEindInVerschillendeMaanden) {
		echo($this->format($kamp->startDatumTijd, "j F"));
	} else {
		echo($this->format($kamp->startDatumTijd, "j"));
	}
	if ($kamp->subgroepsamenstellingMinimum != $kamp->subgroepsamenstellingMaximum) {
		$subgroep = $kamp->subgroepsamenstellingMinimum .' - '. $kamp->subgroepsamenstellingMaximum .' pers';
	} else {
		if ((int)$kamp->subgroepsamenstellingMinimum == 0) {
			$subgroep = 'pers nvt';
		} else {
			$subgroep = $kamp->subgroepsamenstellingMinimum . ' pers';
		}
	}
echo <<< EOT
	- {$this->format($kamp->eindDatumTijd, "j F")}	
	| {$kamp->minimumLeeftijd} - {$kamp->maximumLeeftijd} jaar
	| {$subgroep}
	| € {$kamp->deelnamekosten}
	</p>
	<p style="align: justify; font-family: Helvetica;">{$kamp->hitCourantTekst}</p>
EOT;
	}
	
	
	
	private function hitcourant_colofon($hit) {
		echo <<< EOT
	<div style="padding: 1em; font-family: Helvetica">
	<h1 style="font-family: Helvetica; margin-top: 0em;">Colofon HIT-courant $hit->jaar</h1>
	<p>De HIT-courant verschijnt een keer per jaar en is bestemd voor alle leden van Scouting Nederland.</p>
	
	<p>
	<strong>Redactie:</strong> {$this->courant_contactPersonen($hit->hitPlaatsen)}.
	<br /><strong>Foto’s:</strong> Sebastiaan Westerweel en de diverse HIT-plaatsen
	<br /><strong>Illustratie voorkant:</strong> Bart Jansen
	<br /><strong>Eindredactie:</strong> Maarten Romkes, Sietske Zinkstok-Hoekstra, Lars Vermeij (Team communicatie, Landelijk servicecentrum Scouting Nederland)
	</p>
	
	<p>Scouting Nederland<br />
	Postbus 210 3830 AE Leusden</p>
	
	<p>
	<strong>tel</strong> +31 (0)33 496 09 11<br />
	<strong>e-mail</strong> helpdesk@hit.scouting.nl<br />
	<strong>web</strong> www.hit.scouting.nl<br />
	</p>
	<div>
EOT;
	}

	private function courant_contactPersonen($plaatsen) {
		$result = '';
		for ($i = 0; $i < count($plaatsen); $i++) {
			$result .= $plaatsen[$i]->contactPersoonNaam;
			if ($i +1 < count($plaatsen)) {
				$result.=', ';
			}
		}
		return $result;
	}

	private function icoonLinkerKolom($icoon) {
		return "<tr>" . $this->icoonMiddelKolom($icoon);
	}
	
	
	private function icoonMiddelKolom($icoon) {
		return 
		"<td style=\"border: .2em solid black;\">{$this->icoonHeelGroot($icoon)}</td>" .
		"<td style=\"border: .2em solid black;\"><font style=\"font-family: Helvetica;\">$icoon->tekst</font></td>";
	}
	
	private function icoonRechterKolom($icoon) {
		return $this->icoonMiddelKolom($icoon) . "</tr>";
	}
	
	private function icoonGroot($icoon) {
		return "<img src=\"media/com_kampinfo/images/iconencourant/{$icoon->naam}.png\" alt=\"{$icoon->tekst}\" title=\"{$icoon->tekst}\" width=\"25px\" height=\"25px\" />";
	}
	
	private function icoonHeelGroot($icoon) {
		return "<img src=\"media/com_kampinfo/images/iconencourant/{$icoon->naam}.png\" alt=\"{$icoon->tekst}\" title=\"{$icoon->tekst}\" width=\"40px\" height=\"40px\" />";
	}

	
	private function format($date, $format) {
		$result = $date->format($format);
		$vertaling = array(
				'Monday' => 'maandag',
				'Tuesday' => 'dinsdag',
				'Wednesday' => 'woensdag',
				'Thursday' => 'donderdag',
				'Friday' => 'vrijdag',
				'Saturday' => 'zaterdag',
				'Sunday' => 'zondag',
				'January' => 'januari',
				'Febrary' => 'februari',
				'March' => 'maart',
				'April' => 'april',
				'May' => 'mei',
				'June' => 'juni',
				'July' => 'juli',
				'August' => 'augustus',
				'September' => 'september',
				'October' => 'oktober',
				'November' => 'november',
				'December' => 'december',
		);
	
		foreach ($vertaling as $engels => $nederlands) {
			$result = str_ireplace($engels, $nederlands, $result);
		}
		return $result;
	}
	
}
?>
