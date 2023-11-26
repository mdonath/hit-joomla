<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Helper;

defined('_JEXEC') or die('Restricted access');

use HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld\DatumVeld;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld\GewoonVeld;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld\GeslachtVeld;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\Veld\IgnoredVeld;


/**
 * Mapt velden uit SOL naar het eigen model.
 */
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
                , 'Formuliernaam' => new GewoonVeld('formulierNaam')
                , 'Aantal dln\'s' => new GewoonVeld('aantalDeelnemers')
                , 'Minimum aantal deelnemers' => new GewoonVeld('minimumAantalDeelnemers')
                , 'Maximum aantal deelnemers' => new GewoonVeld('maximumAantalDeelnemers')
                , 'Minimum leeftijd' => new GewoonVeld('minimumLeeftijd')
                , 'Maximum leeftijd' => new GewoonVeld('maximumLeeftijd')
                , 'Gereserveerd' => new GewoonVeld('gereserveerd')
                , 'Subgroepen' => new GewoonVeld('aantalSubgroepen')
                , 'Subgroepcategorie' => new GewoonVeld('subgroepcategorie')
            );
        }
        return $mapping;
    }

}
