<?php

namespace HITScoutingNL\Component\KampInfo\Site\View\Kiezer;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Uri\Uri;


/**
 * HTML View class voor de HIT Kiezer.
 */
class HtmlView extends BaseHtmlView {

    public function display($tpl = null) {
        $this->project = $this->get('Project');

        $document = Factory::getApplication()->getDocument();

        $wa = $document->getWebAssetManager();

        $params = ComponentHelper::getParams('com_kampinfo');
        $iconFolderLarge = $params->get('iconFolderLarge');
        $iconExtension = $params->get('iconExtension');

        self::integerifyFields($this->project);
        $json = json_encode($this->project);

        $wa
            -> useStyle('com_kampinfo-hitkiezer')
            -> useScript('com_kampinfo-jquery-cookies')
            -> addInlineScript("var hit = $json")
            -> useScript('com_kampinfo-common')
            -> useScript('com_kampinfo-hitkiezer')
            -> addInlineScript('kampinfoConfig.iconFolderLarge = "'.URI::root().$iconFolderLarge . '";')
            -> addInlineScript('kampinfoConfig.iconExtension="'. $iconExtension .'";')
        ;

        return parent::display($tpl);
    }

    /**
     * Maakt van velden (waar dat van nodig is) een integer om juiste json-encoding te krijgen.
     * Anders worden het strings en dan gaat het mis met vergelijkingen.
     * 
     * @param unknown $project
     */
    private static function integerifyFields($project) {
        // Alle velden in een array zodat ze in een loopje omgezet kunnen worden
        $kampFields = [
            'shantiFormuliernummer',
            'ouderShantiFormuliernummer',
            'extraShantiFormuliernummer',
            'minimumLeeftijd',
            'maximumLeeftijd',
            'minimumLeeftijdOuder',
            'maximumLeeftijdOuder',
            'deelnamekosten',
            'minimumAantalDeelnemers',
            'maximumAantalDeelnemers',
            'aantalDeelnemers',
            'gereserveerd',
            'subgroepsamenstellingMinimum',
            'margeAantalDagenTeJong',
            'margeAantalDagenTeOud',
            'aantalSubgroepen',
            'maximumAantalSubgroepjes',
            'isouderkind'
        ];

        $project->jaar = intval($project->jaar);
        foreach ($project->hitPlaatsen as $plaats) {
            foreach ($plaats->kampen as $kamp) {
                foreach ($kampFields as $field) {
                    $kamp->$field = intval($kamp->$field);
                }
            }
        }
    }

}