<?php

namespace HITScoutingNL\Library\KampInfo\Helper;

\defined('_JEXEC') or die('Restricted access');

use DateTimeZone;
use Joomla\CMS\Factory;
use Joomla\CMS\Date\Date;


/**
 * KampInfo component helper.
 */
abstract class KampInfoHelper {

    /**
     * Geeft alle mogelijke activiteit-gebieden als objecten met een value en een text.
     * 
     * @return object[]
     */
    public static function getActivityAreaOptions() {
        return [
            (object) [
                "value" => "buitenleven",
                "text" => "Buitenleven"
            ],
            (object) [
                "value" => "expressie",
                "text" => "Expressie"
            ],
            (object) [
                "value" => "identiteit",
                "text" => "Identiteit"
            ],
            (object) [
                "value" => "internationaal",
                "text" => "Internationaal"
            ],
            (object) [
                "value" => "samenleving",
                "text" => "Samenleving"
            ],
            (object) [
                "value" => "sportenspel",
                "text" => "Sport en Spel"
            ],
            (object) [
                "value" => "uitdagend",
                "text" => "Uitdagende Scoutingtechnieken"
            ],
            (object) [
                "value" => "veiligengezond",
                "text" => "Veilig en Gezond"
            ]
        ];
    }

    /**
     * Geeft alle mogelijke categorieën van icoontjes in een Map met de code als key en de omschrijving als value.

     * @return array{?: string, A: string, B: string, I: string, K: string, O: string, S: string}
     */
    public static function getHitIconSoortOptions() {
        return [
                "?" => "Gewoon",
                "B" => "Beweging",
                "I" => "Inschrijven",
                "O" => "Overnachten",
                "A" => "Afstand",
                "K" => "Koken",
                "S" => "Systeem"
        ];
    }

    /**
     * Formatteert een datum in yyyy-mm-dd formaat naar het dd-mm-yyyy formaat.

     * @param mixed $date
     * @param mixed $metTijd Of de tijd ook meegenomen moet worden
     * @return mixed
     */
    public static function reverse($date, $metTijd=false) {
        if ($date != '0000-00-00') {
            $date = new Date($date);
            $date->setTimezone(self::getTimeZone());
            $format = 'd-m-Y';
            if ($metTijd) {
                $format .= ' H:i';
            }
            return $date->format($format, true);
        }
        return $date;
    }

    /**
     * Returns the userTime zone if the user has set one, or the global config one
     * 
     * @return mixed
     */
    public static function getTimeZone() {
        $timeZone = '';
        $userTz = Factory::getUser()->getParam('timezone');
        if ($userTz) {
            $timeZone = $userTz;
        } else {
            $timeZone = Factory::getConfig()->get('offset');
        }
        return new DateTimeZone($timeZone);
    }

    /**
     * Berekent hoeveel overnachtingen een deelnemer zal hebben bij een kamponderdeel op basis van de start- en einddatum van het kamp.

     * @param mixed $kamp
     */
    public static function aantalOvernachtingen($kamp) {
        $start = self::clearTime($kamp->startDatumTijd);
        $eind = self::clearTime($kamp->eindDatumTijd);
        return $start->diff($eind)->days;
    }

    private static function clearTime($datumTijd) {
        $datum = new Date($datumTijd);
        $datum->setTimezone(self::getTimezone());
        $datum->setTime(0,0,0);
        return $datum;
    }

    /**
     * Berekent de startdag van een kamp in het volgende HIT jaar op basis van wanneer het startte in het afgelopen jaar.
     * 
     * @param mixed $datum
     * @return string
     */
    public static function herberekenDatum($datum) {
        $DATABASE_DATETIMEFORMAT = 'Y-m-d H:i:s';
        $origineel = new Date($datum);
        $vorigJaar = (int) $origineel->format('Y');
        $diffStart = self::eersteHitDag($vorigJaar)->diff($origineel);
        $nieuweStart = self::eersteHitDag($vorigJaar + 1)->add($diffStart);
        return $nieuweStart->format($DATABASE_DATETIMEFORMAT);
    }

    /**
     * Levert de datum van de eerste HIT dag (lees: Goede Vrijdag).
     * 
     * Er is een functie \easter_date(), maar die vereist de calendar-library in PHP.
     * 
     * @param int $jaar Het jaar waarvan je de eerste HIT dag wil hebben
     * @return \DateTime|bool De datum van de eerste HIT dag en in theorie null als het mislukt.
     */
    public static function eersteHitDag(int $jaar) {
        $goedeVrijdag = [
            2004 => '09-04-2004',
            2005 => '25-03-2005',
            2006 => '14-04-2006',
            2007 => '06-04-2007',
            2008 => '21-03-2008',
            2009 => '10-04-2009',
            2010 => '02-04-2010',
            2011 => '22-04-2011',
            2012 => '06-04-2012',
            2013 => '29-03-2013',
            2014 => '18-04-2014',
            2015 => '03-04-2015',
            2016 => '25-03-2016',
            2017 => '14-04-2017',
            2018 => '30-03-2018',
            2019 => '19-04-2019',
            2020 => '10-04-2020',
            2021 => '02-04-2021',
            2022 => '15-04-2022',
            2023 => '07-04-2023',
            2024 => '29-03-2024',
            2025 => '18-04-2025',
            2026 => '03-04-2026',
            2027 => '26-03-2027',
            2028 => '14-04-2028',
            2029 => '30-03-2029',
            2030 => '19-04-2030',
            2031 => '11-04-2031',
            2032 => '26-03-2032',
            2033 => '15-04-2033',
            2034 => '07-04-2034',
            2035 => '23-03-2035',
            2036 => '11-04-2036',
            2037 => '03-04-2037',
        ];
        return \DateTime::createFromFormat('d-m-Y|', $goedeVrijdag[$jaar]);
    }

    /**
     * Geeft terug of we in de gehele inschrijfperiode zitten, maar houdt geen rekening met de loterij-fase die binnen deze periode valt.
     * 
     * @param mixed $activiteit
     * @return bool
     */
    public static function isInschrijvingActief($activiteit) {
        return KampInfoHelper::isActief(
            $activiteit->startInschrijving,
            $activiteit->eindInschrijving
        );
    }

    /**
     * Geeft terug of we nu in de fase loterij zitten op het moment 'now'.
     * 
     * @param mixed $activiteit
     * @return bool
     */
    public static function isLoterijActief($activiteit) {
        return KampInfoHelper::isActief(
            $activiteit->startLoterij,
            $activiteit->eindLoterij
        );
    }

    /**
     * Je komt op de wachtlijst vanaf het begin van de inschrijving tot aan het begin van de loterij.
     * 
     * @param mixed $activiteit
     * @return bool
     */
    public static function isWachtlijstActief($activiteit) {
        return KampInfoHelper::isActief(
            $activiteit->startInschrijving,
            $activiteit->startLoterij
        );
    }

    /**
     * Valt het moment 'nu' in de opgegeven range tussen $beginMoment en $eindMoment.
     * 
     * @param mixed $beginMoment
     * @param mixed $eindMoment
     * @return bool
     */
    private static function isActief($beginMoment, $eindMoment) {
        $nu = (new Date('now'))->getTimestamp();
        return  $nu >= ((new Date($beginMoment))->getTimestamp()) 
                && $nu <= ((new Date($eindMoment))->getTimestamp());
    }

}
