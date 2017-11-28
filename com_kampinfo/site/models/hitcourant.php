<?php defined('_JEXEC') or die('Restricted access');

include_once dirname(__FILE__) . '/kampinfomodelparent.php';


function kamp_sort_on_minimum_leeftijd($a, $b) {
	$result = $a->minimumLeeftijd - $b->minimumLeeftijd;
	if ($result == 0) {
		$result = $a->maximumLeeftijd - $b->maximumLeeftijd;
		if ($result == 0) {
			$result = strcmp($a->naam, $b->naam);
		}
	}
	return $result;
}

/**
 * KampInfo HitCourant Model.
 */
class KampInfoModelHitCourant extends KampInfoModelParent {

	public function generate() {
		$params =JComponentHelper::getParams('com_kampinfo');

		if ($this->magHet($params)) {
			$projectId = $params->get('huidigeActieveJaar');
			$this->hitcourant($this->getHitData($projectId));
		} else {
			echo "What's the magic word?";
		}
	}

	//////////////////////////////////////
	function getHitPlaatsen($projectId) {
		$db = JFactory::getDBO();
	
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__kampinfo_hitsite s');
		$query->where('(s.hitproject_id = ' . (int) ($db->escape($projectId)) . ')');
		$query->order('s.naam');
	
		$db->setQuery($query);
		$plaatsenInJaar = $db->loadObjectList();
		return $plaatsenInJaar;
	}
	function getHitKampen($hitsiteId, $iconenLijst) {
		$db = JFactory::getDBO();
	
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__kampinfo_hitcamp c');
		$query->where('(c.hitsite_id = ' . (int)($db->escape($hitsiteId)) . ')');
		$query->where('(c.akkoordHitKamp=1 and c.akkoordHitPlaats=1)');
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
		$this->convertDateTime($project, array('vrijdag', 'maandag', 'inschrijvingStartdatum', 'inschrijvingEinddatum'));
		$project->heeftBeginEnEindInVerschillendeMaanden = $project->vrijdag->format('m') != $project->maandag->format('m');
	}

	private function convertDateTime($object, $datumVelden) {
		$UTC = new DateTimeZone("UTC");
		$tz = new DateTimeZone("Europe/Amsterdam");
		foreach ($datumVelden as $veld) {
			$localDate = DateTime::createFromFormat('Y-m-d H:i:s', $object->$veld, $UTC);
			$localDate->setTimezone($tz);
			$object->$veld = $localDate; 
		}
	}

	private function preparePlaatsen($project, $plaatsen) {
		$project->hitPlaatsen = $plaatsen;
		$iconenLijst = array();
		foreach ($plaatsen as $plaats) {
			$kampen = $this->getHitKampen($plaats->id, $iconenLijst);
			$plaats->hitKampen = $kampen;
			foreach ($kampen as $kamp) {
				$this->prepareKamp($kamp, $plaats);
			}
		}
	}

	private function prepareKamp($kamp, $plaats) {
		$this->convertDateTime($kamp, array('startDatumTijd', 'eindDatumTijd'));
		$kamp->heeftBeginEnEindInVerschillendeMaanden = $kamp->startDatumTijd->format('m') != $kamp->eindDatumTijd->format('m');
		$kamp->plaats = $plaats;
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
		echo <<< EOT
	<h1 style="text-align: center; font-family: Impact; color: #005eb0; margin-top: .75em; margin-bottom: 0em; font-weight: normal; font-size:18pt;">HIT $hit->jaar: $hit->thema</h1>
	<p style="font-family: Helvetica; font-size:8pt;"><strong>
		Jij bent een scout die eruit wil halen wat erin zit: je wilt nieuwe avonturen beleven, je vindt het leuk om andere scouts te leren kennen
		en je verbetert graag je Scoutingskills. Dan is de HIT iets voor jou. Want in het paasweekend van $hit->jaar wachten er meer
		dan 60 verschillende, spannende en uitdagende activiteiten op jou, verspreid over heel Nederland!
	</strong></p>

	<p style="font-family: Helvetica; font-size:8pt;">
	De HIT staat voor Hikes, Interessekampen en Trappersexpedities en wordt elk jaar gehouden tijdens de paasdagen. Een paar duizend
	scouts tussen de 5 en de 88 jaar beleven een ultieme Scoutingactiviteit waarin je alles kunt tegenkomen wat Scouting te bieden heeft.
	In $hit->jaar vindt de HIT plaats van 
EOT;
	
	if ($hit->heeftBeginEnEindInVerschillendeMaanden) {
		echo ($this->format($hit->vrijdag, "l j F"));
	} else {
		echo ($this->format($hit->vrijdag, "l j"));
	}
	
echo <<< EOT
	tot en met {$this->format($hit->maandag, "l j F")}. Vanaf {$this->format($hit->inschrijvingStartdatum, "j F")}  kun je je inschrijven.
	Wacht niet te lang met inschrijven, want elke HIT heeft maar een beperkt aantal plaatsen.
	Lees snel verder in deze HIT-courant of kijk op de website uit welke te gekke activiteiten jij en je Scoutingvrienden dit jaar kunnen kiezen!
	<br><i>Tip: De HIT-courant is ook via de ScoutingApp te bekijken (zowel Android als iPhone). Meer info vind je op de HIT-website.</i>
	</p>
	
	<div style="font-family: Helvetica; border: .2em solid black; padding: 1em; font-size:8pt;">
		<h1  style="font-family: Impact; color: #005eb0; margin-top: .75em; margin-bottom: 0em; font-weight: normal; text-align: center; margin-top: 0em; font-size:18pt;">Hoe kan ik me inschrijven?</h1>
		<p>Heb je je keuze gemaakt? Op de HIT-website <a href="https://hit.scouting.nl/">hit.scouting.nl</a> vind je vanaf
		1 januari meer informatie over elke HIT-activiteit. Onderaan elke beschrijving vind je – vanaf de inschrijfdatum – ook
		meteen een link naar het inschrijfformulier. Log hiervoor wel eerst even in op de website van Scouting Nederland.
		Kom je er niet goed uit? Een handleiding vind je op de HIT-website. Ook kun je via de website contact opnemen met de HIT-helpdesk.<br> 
		
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
	<h2 style="text-align: center; color: green; font-style: italic; font-family: Helvetica; font-size:18pt;">Verklaring van de symbolen in deze HIT-courant</h2>
	
	<table style="border-collapse: collapse; font-family: Helvetica; font-size:8pt;">
	{$this->print_iconen($hit->gebruikteIconenVoorCourant)}
	</table>
	
	<p style="text-align: center; font-family: Helvetica; font-size:8pt;">Wijzigingen voorbehouden, kijk op de website bij de activiteit voor de laatste informatie</p>
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
		$sortedKampen = array();
		foreach ($hit->hitPlaatsen as $plaats) {
			foreach ($plaats->hitKampen as $kamp) {
				$sortedKampen[] = $kamp;
			}
			$this->hitcourant_plaats($plaats);
		}
		// Verzamel kampgegevens van alle HIT plaatsen en sorteer op leeftijd
		usort($sortedKampen, "kamp_sort_on_minimum_leeftijd");
		foreach ($sortedKampen as $kamp) {
			$this->hitcourant_kamp($kamp);
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
	<p style="font-family: Helvetica; font-size:8pt;"><img src="images/headers/hit_logo_h_web_{$strtolower($plaats->naam)}.png"></p>
	<p style="font-family: Helvetica; font-size:8pt;">{$plaats->hitCourantTekst}</p>
EOT;
		echo('<p style="font-family: Helvetica; font-size:8pt;">');
		$sortedKampen = $plaats->hitKampen;
		usort($sortedKampen, "kamp_sort_on_minimum_leeftijd");
		
		foreach ($sortedKampen as $kamp) {
			$this->hitcourant_kamp_naam_leeftijd($kamp);
		}
		echo('</p>');
echo <<< EOT
		<p style="font-family: Helvetica; font-size:8pt;">Meer info: <a href="https://hit.scouting.nl/{$strtolower($plaats->naam)}">https://hit.scouting.nl/{$strtolower($plaats->naam)}</a></p>
EOT;
	}
	
	/**
	 * Print de gegevens van een kamp.
	 *
	 * @param unknown $kamp
	 */
	private function hitcourant_kamp_naam_leeftijd($kamp) {
		echo($kamp->naam .' | '. $kamp->minimumLeeftijd .'-'. $kamp->maximumLeeftijd); 
		if ($kamp->isouderkind == '1') {
			echo(' / '. $kamp->minimumLeeftijdOuder .'-'. $kamp->maximumLeeftijdOuder); 
		}
		echo " jaar<br>";
	}

	/**
	 * Print de gegevens van een kamp.
	 * 
	 * @param unknown $kamp
	 */
	private function hitcourant_kamp($kamp) {
		
echo <<< EOT
<h1 style="font-family: Impact; color: #005eb0; margin-top: .75em; margin-bottom: 0em; font-weight: normal; font-size:18pt;">$kamp->naam</h1>
<p style="text-align: right; margin-left: .1em;">
EOT;
		foreach ($kamp->icoontjes as $icoon) {
			echo($this->icoonGroot($icoon));
		}

		// Start en Eind:
		$startDatumTijd = substr($this->format($kamp->startDatumTijd, "l"), 0, 2);
		$eindDatumTijd = substr($this->format($kamp->eindDatumTijd, "l"), 0, 2);
		$startEind = $startDatumTijd .'-'. $eindDatumTijd;

		// Leeftijden:
		if ($kamp->isouderkind == '1') {
			$leeftijden = "{$kamp->minimumLeeftijd}-{$kamp->maximumLeeftijd}/{$kamp->minimumLeeftijdOuder}-{$kamp->maximumLeeftijdOuder} jr";
		} else {
			$leeftijden = "{$kamp->minimumLeeftijd}-{$kamp->maximumLeeftijd} jr";
		}

		// Inschrijven per...
		if ($kamp->subgroepsamenstellingMinimum != $kamp->subgroepsamenstellingMaximum) {
			$subgroep = $kamp->subgroepsamenstellingMinimum .'-'. $kamp->subgroepsamenstellingMaximum .' p';
		} else {
			if ((int)$kamp->subgroepsamenstellingMinimum == 0) {
				$subgroep = '1 p'; // individuele inschrijving?
			} else {
				$subgroep = $kamp->subgroepsamenstellingMinimum . ' p';
			}
		}
		
echo <<< EOT
</p>
	<p style="text-align: right; font-weight: bold; font-family: Helvetica; font-weight: bold; font-size:8pt;">
		{$kamp->plaats->naam} | {$startEind} | {$leeftijden} | {$subgroep} | €&nbsp;{$kamp->deelnamekosten}
	</p>
	<p style="align: justify; font-family: Helvetica; font-size:8pt;">{$kamp->hitCourantTekst}</p>
EOT;
	}

	private function hitcourant_colofon($hit) {
		echo <<< EOT
	<div style="padding: 1em; font-family: Helvetica; font-size:8pt;">
	<h1 style="font-family: Helvetica; margin-top: 0em; font-size:18pt;">Colofon HIT-courant $hit->jaar</h1>
	<p>De HIT-courant verschijnt één keer per jaar en is bestemd voor alle leden van Scouting Nederland.</p>
	
	<p>
	<strong>Redactie:</strong> {$this->courant_contactPersonen($hit->hitPlaatsen)}.
	<br /><strong>Foto’s:</strong> Nog invullen en de diverse HIT-plaatsen
	<br /><strong>Illustratie voorkant:</strong> Nog invullen
	<br /><strong>Eindredactie:</strong> Maarten Romkes, Bernhard Bergman, Lisette van Garder (Team communicatie, servicecentrum Scouting Nederland)
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
		"<td style=\"border: .2em solid black;\"><font style=\"font-family: Helvetica; font-size:8pt;\">$icoon->tekst</font></td>";
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
				'February' => 'februari',
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
