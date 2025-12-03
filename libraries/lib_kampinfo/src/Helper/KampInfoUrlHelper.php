<?php

namespace HITScoutingNL\Library\KampInfo\Helper;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Date\Date;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;

/**
 * KampInfo url helper.
 */
abstract class KampInfoUrlHelper {

    public static function imgUrl($folder, $naam, $ext, $title, $alt = '') {
        return HTMLHelper::_('image', $folder .'/'. $naam . $ext, $alt, ['title' => $title]);
    }

    public static function isYoutubeFilmpje($url) {
        $dotcom = strpos($url, 'www.youtube.com') !== false;
        $dotbe = strpos($url, 'youtu.be') !== false;
        return $dotcom || $dotbe;
    }

    public static function isEmptyUrl($url) {
        return empty($url) or $url == 'http://';
    }

    public static function activiteitURL($plaats, $kamp, $jaar, $use = TRUE) {
        if ($use) {
            $hitcampId = $kamp->id;
            return "index.php?option=com_kampinfo&amp;view=activiteit&amp;hitcamp_id=$hitcampId";
        } else {
            return KampInfoUrlHelper::plaatsURL($plaats, $jaar, $use, FALSE) . "/" . KampInfoUrlHelper::aliassify($kamp);
        }
    }

    public static function plaatsURL($plaats, $jaar, $use = TRUE, $overzicht = TRUE) {
        if ($use) {
            $code = $plaats->id;
            return "index.php?option=com_kampinfo&amp;view=overzichtplaats&amp;hitsite_id=$code";
        } else {
            $overzichtExtra = '';
            if ($overzicht) {
                $overzichtExtra = '/overzicht';
            }
            if ($jaar == NULL) {
                return "hits-in-" . strtolower($plaats->naam) . $overzichtExtra;
            } else {
                return "hits-in-" . strtolower($plaats->naam) . '-' . $jaar . $overzichtExtra;
            }
        }
    }

    public static function aliassify($kamp) {
        return OutputFilter::stringURLSafe($kamp->naam);
    }

    public static function fuzzyIndicatieVol($kamp) {
        if (KampInfoUrlHelper::isVol($kamp)) {
            if (KampInfoUrlHelper::volOfLoterij($kamp) == 'loterij') {
                $result = "Er zijn meer aanmeldingen dan plaatsen, er gaat geloot worden!";
            } else {
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

    public static function volOfLoterij($kamp) {
        $nu = (new Date('now'))->getTimestamp();
        $loterijLoopt = $nu >= (new Date($kamp->startLoterij))->getTimestamp() && $nu <= (new Date($kamp->eindLoterij))->getTimestamp();
        if ($loterijLoopt) {
            return 'loterij';
        }   
        return 'vol';
    }

}
