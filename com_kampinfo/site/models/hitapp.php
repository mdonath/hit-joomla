<?php defined('_JEXEC') or die('Restricted access');

include_once dirname(__FILE__) . '/kampinfomodelparent.php';
require_once JPATH_COMPONENT_ADMINISTRATOR .'/../com_kampinfo/helpers/kampinfourl.php';

/**
 * KampInfo HitApp Model.
 */
class KampInfoModelHitApp extends KampInfoModelParent {

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
		$query->where('(c.published=1 and c.akkoordHitKamp=1 and c.akkoordHitPlaats=1)');
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
					$result[$icon->volgorde] = $icon;
					/*
					if ($icon->soort != 'A') { 
						$result[$icon->volgorde] = $icon;
					} else {
						if (!$afstand and $icon->naam != '0km') {
							$afstand = true;
							$result[$icon->volgorde] = $icon;
						}
					}
					*/
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
{	"project": {
		  "id" : {$hit->id}
		, "jaar": {$hit->jaar}
		, "vrijdag": "{$this->format($hit->vrijdag, "Y-m-d")}"
		, "maandag": "{$this->format($hit->maandag, "Y-m-d")}"
		, "inschrijvingStartdatum": "{$this->format($hit->inschrijvingStartdatum, "Y-m-d")}"
		, "inschrijvingEinddatum": "{$this->format($hit->inschrijvingEinddatum, "Y-m-d")}"
		, "gebruikteIconen" : [
			{$this->print_iconen($hit->gebruikteIconenVoorCourant)}
		]

EOT;
		$this->hitcourant_krant($hit);
		echo <<< EOT
	}
}
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
		$sep='  ';
		foreach($iconen as $index => $icoon) {
			$result .= $sep . $this->print_icoon($icoon);
			$sep="\n\t\t\t, ";
		}
		return $result;
	}
	
	/**
	 * Print de gegevens van alle HIT-Plaatsen.
	 * 
	 * @param unknown $hit
	 */
	private function hitcourant_krant($hit) {
		echo <<< EOT
		, "plaatsen": [
EOT;
		$sep = '  ';
		foreach ($hit->hitPlaatsen as $plaats) {
			$this->hitcourant_plaats($plaats, $sep);
			$sep = ', ';
		}
		echo <<< EOT
		]
EOT;
	}
	
	/**
	 * Print de gegevens van een HIT-PLaats.
	 * 
	 * @param unknown $plaats
	 */
	private function hitcourant_plaats($plaats, $sep) {
echo <<< EOT
	$sep {
		  "id" : $plaats->id 
		, "naam" : "{$this->sanitizeText($plaats->naam)}"
		, "hitCourantTekst" : "{$this->sanitizeText($plaats->hitCourantTekst)}"
		, "kampen" : [

EOT;
		$sep = '  ';
		foreach ($plaats->hitKampen as $kamp) {
			$this->hitcourant_kamp($kamp, $sep);
			$sep = ', ';
		}
echo <<< EOT
		]
	}
EOT;
	}

	/**
	 * Print de gegevens van een kamp.
	 * 
	 * @param unknown $kamp
	 */
	private function hitcourant_kamp($kamp, $sep) {
		if ($kamp->subgroepsamenstellingMinimum != $kamp->subgroepsamenstellingMaximum) {
			$subgroep = $kamp->subgroepsamenstellingMinimum .' - '. $kamp->subgroepsamenstellingMaximum .' pers';
		} else {
			if ((int)$kamp->subgroepsamenstellingMinimum == 0) {
				$subgroep = 'pers nvt';
			} else {
				$subgroep = $kamp->subgroepsamenstellingMinimum . ' pers';
			}
		}

		$vol = KampInfoUrlHelper::isVol($kamp) ? "true" : "false";
		$volTekst = KampInfoUrlHelper::fuzzyIndicatieVol($kamp);
		
		echo <<< EOT
		$sep {
			  "id" : $kamp->id
			, "naam": "{$this->sanitizeText($kamp->naam)}"
			, "startDatumTijd": "{$this->format($kamp->startDatumTijd, "Y-m-d H:i")}"
			, "eindDatumTijd": "{$this->format($kamp->eindDatumTijd, "Y-m-d H:i")}"
			, "margeTeJong": {$kamp->margeAantalDagenTeJong}
			, "minimumLeeftijd": {$kamp->minimumLeeftijd}
			, "maximumLeeftijd": {$kamp->maximumLeeftijd}
			, "margeTeOud": {$kamp->margeAantalDagenTeOud}
			, "subgroep": "{$subgroep}"
			, "deelnamekosten": "{$kamp->deelnamekosten}"
			, "hitCourantTekst": "{$this->sanitizeText($kamp->hitCourantTekst)}"
			, "minAantalDeelnemers": {$kamp->minimumAantalDeelnemers}
			, "maxAantalDeelnemers": {$kamp->maximumAantalDeelnemers}
			, "vol": {$vol}
			, "volTekst": "{$this->sanitizeText($volTekst)}"
			, "minSubgroep": {$kamp->subgroepsamenstellingMinimum}
			, "icoontjes": [

EOT;
		$sep = "\t\t\t\t  ";
		foreach ($kamp->icoontjes as $icoon) {
			echo($sep . $this->print_icoon_bij_kamp($icoon));
			$sep = "\n\t\t\t\t, ";
		}
		echo <<< EOT
			]
			}
		
EOT;
	}

	private function print_icoon($icoon) {
		return '{ "naam": "' . $icoon->naam . '", "tekst": "' . $icoon->tekst . '"}';
	}

	private function print_icoon_bij_kamp($icoon) {
		return '"' . $icoon->naam . '"';
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
	

	private function sanitizeText($text) {
		return $this->escapeJsonString(utf8_decode($text));
	}
	
	private function escapeJsonString($value) { # list from www.json.org: (\b backspace, \f formfeed)
		$escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
		$replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
		$result = str_replace($escapers, $replacements, $value);
		return $result;
	}
}
?>
