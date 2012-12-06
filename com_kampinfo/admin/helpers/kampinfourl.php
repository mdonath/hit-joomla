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

	public static function activiteitURL($plaats, $kamp, $use = TRUE) {
		if ($use) {
			$jaar = $plaats->jaar;
			$deelnemersnummer = $kamp->deelnemersnummer;
			return "index.php?option=com_kampinfo&amp;view=activiteit&amp;jaar=$jaar&amp;deelnemersnummer=$deelnemersnummer";
		} else {
			return KampInfoUrlHelper::plaatsURL($plaats, $use) . "/" . KampInfoUrlHelper::aliassify($kamp);
		}
	}

	public static function plaatsURL($plaats, $use = TRUE) {
		if ($use) {
			$code = $plaats->code;
			return "index.php?option=com_kampinfo&amp;view=overzichtplaats&amp;plaats=$code";
		} else {
			return "hits-in-" . strtolower($plaats->naam);
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

}