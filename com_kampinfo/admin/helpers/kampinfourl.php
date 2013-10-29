<?php
defined('_JEXEC') or die;

/**
 * KampInfo url helper.
 */
abstract class KampInfoUrlHelper {

	public static function imgUrl($folder, $naam, $ext = '.gif', $title, $alt = '') {
		return '<img src="' . JURI :: root() . $folder . '/' . $naam . $ext . '" title="' . $title . '" alt="' . $alt . '"/>';
	}

	public static function isYoutubeFilmpje($url) {
		$dotcom = strpos($url, 'www.youtube.com') !== false;
		$dotbe = strpos($url, 'youtu.be') !== false;
		return $dotcom || $dotbe;
	}

	public static function isEmptyUrl($url) {
		return empty ($url) or $url == 'http://';
	}

	public static function activiteitURL($plaats, $kamp, $jaar, $use = TRUE) {
		if ($use) {
			$hitcampId = $kamp->id;
			return "index.php?option=com_kampinfo&amp;view=activiteit&amp;hitcamp_id=$hitcampId";
		} else {
			return KampInfoUrlHelper::plaatsURL($plaats, $jaar, $use) . "/" . KampInfoUrlHelper::aliassify($kamp);
		}
	}

	public static function plaatsURL($plaats, $jaar, $use = TRUE) {
		if ($use) {
			$code = $plaats->id;
			return "index.php?option=com_kampinfo&amp;view=overzichtplaats&amp;hitsite_id=$code";
		} else {
			if ($jaar == NULL) {
				return "hits-in-" . strtolower($plaats->naam);
			} else {
				return "hits-in-" . strtolower($plaats->naam) . '-' . $jaar;
			}
		}
	}

	public static function aliassify($kamp) {
		$pat = array ();
		$rep = array ();

		$pat[] = '/ - /';
		$rep[] = '-';
		$pat[] = '/ /';
		$rep[] = '-';
		$pat[] = '/[^a-z0-9\-]/';
		$rep[] = '';
		$pat[] = '/-+/';
		$rep[] = '-';

		return preg_replace($pat, $rep, strtolower($kamp->naam));
	}
	

	public static function fuzzyIndicatieVol($kamp) {
		if (KampInfoUrlHelper::isVol($kamp)) {
			if (intval($kamp->aantalDeelnemers) < intval($kamp->gereserveerd)) {
				$eenAantal = $kamp->gereserveerd - $kamp->aantalDeelnemers;
				$result = "Vol: alleen nog inschrijven op ". $eenAantal ." gereserveerde ". KampInfoUrlHelper::meervoudPlaats($eenAantal) .".";
			} else {
				if (self::isVolQuaGroepjes($kamp)) {
					$result = "Vol: Maximum aantal groepjes is bereikt. Inschrijven is niet meer mogelijk";
				} else {
					$result = "Vol: inschrijven is niet meer mogelijk.";
				}
			}
		} else {
			$watMaaktHetKampVol = max($kamp->maximumAantalDeelnemers/10, $kamp->subgroepsamenstellingMinimum);
			$over = $kamp->maximumAantalDeelnemers - $kamp->gereserveerd;
			if ($watMaaktHetKampVol < $over) {
				if ($kamp->gereserveerd == 0) {
					$result = "Nog ruim voldoende plaatsen beschikbaar.";
				} else {
					$result = "Nog voldoende plaatsen beschikbaar.";
				}
			} else {
				$result = "Bijna vol: Nog ". $over ." ". KampInfoUrlHelper::meervoudPlaats($over) ." beschikbaar.";
			}
		}
		return $result;
	}
	public static function meervoudPlaats($eenAantal) {
		return "plaats". (($eenAantal!=1) ? "en" : "");
	}
	
	public static function isVol($kamp) {
		return intval($kamp->gereserveerd) >= intval($kamp->maximumAantalDeelnemers) || self::isVolQuaGroepjes($kamp);
	}
	
	public static function isVolQuaGroepjes($kamp) {
		return intval($kamp->maximumAantalSubgroepjes) > 0 && intval($kamp->aantalSubgroepen) >= intval($kamp->maximumAantalSubgroepjes);	
	}

}