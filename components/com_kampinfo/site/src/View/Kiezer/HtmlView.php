<?php

namespace HITScoutingNL\Component\KampInfo\Site\View\Kiezer;

use HITScoutingNL\Library\KampInfo\Helper\KampInfoHelper;

\defined('_JEXEC') or die('Restricted Access');

use DateTimeZone;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Uri\Uri;


/**
 * HTML View class voor de HIT Kiezer.
 */
class HtmlView extends BaseHtmlView {

    protected $project;

    public function display($tpl = null) {
        $model      = $this->getModel();
        $app        = Factory::getApplication();
        $document   = $app->getDocument();
        $wa         = $document->getWebAssetManager();

        $this->project = $model->getProject();
        self::integerifyFields($this->project);
        self::convertAllDatesToLocal($this->project);

        $params = ComponentHelper::getParams('com_kampinfo');

        $document->addScriptOptions(
            'com_kampinfo-hitkiezer.vars',
            [
                'iconFolderLarge' => URI::root() . $params->get('iconFolderLarge'),
                'iconExtension' => $params->get('iconExtension'),
                'hit' => $this->project,
            ]
        );

        $wa ->useStyle('com_kampinfo-hitkiezer.styles')
            // ->useScript('com_kampinfo-jquery-cookies')
            ->useScript('com_kampinfo-hitkiezer.scripts')
        ;

        return parent::display($tpl);
    }

    private static function convertAllDatesToLocal($project) {
        $localTz = KampInfoHelper::getTimeZone();

        $project->vrijdag = self::convertFromUtcToLocal($localTz, $project->vrijdag);
        $project->maandag = self::convertFromUtcToLocal($localTz, $project->maandag);

        foreach ($project->hitPlaatsen as $plaats) {
            foreach ($plaats->kampen as $kamp) {
                $kamp->startInschrijving = self::convertFromUtcToLocal($localTz, $kamp->startInschrijving);
                $kamp->eindInschrijving = self::convertFromUtcToLocal($localTz, $kamp->eindInschrijving);
                $kamp->startLoterij = self::convertFromUtcToLocal($localTz, $kamp->startLoterij);
                $kamp->eindLoterij = self::convertFromUtcToLocal($localTz, $kamp->eindLoterij);
                $kamp->startDatumTijd = self::convertFromUtcToLocal($localTz, $kamp->startDatumTijd);
                $kamp->eindDatumTijd = self::convertFromUtcToLocal($localTz, $kamp->eindDatumTijd);
            }
        }

    }
    private static function convertFromUtcToLocal(DateTimeZone $localTz, ?string $utcDateStr): ?string {
        if (empty($utcDateStr)) {
            return null;
        }

        $utcTz = new DateTimeZone('UTC');
        $date = new \DateTime($utcDateStr, $utcTz);
        $date->setTimezone($localTz);
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Maakt van velden (waar dat van nodig is) een integer om juiste json-encoding te krijgen.
     * Anders worden het strings en dan gaat het mis met vergelijkingen.
     * 
     * @param $project
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
            'isouderkind',
        ];

        $project->jaar = \intval($project->jaar);
        foreach ($project->hitPlaatsen as $plaats) {
            foreach ($plaats->kampen as $kamp) {
                foreach ($kampFields as $field) {
                    if (property_exists($kamp, $field)) {
                        $kamp->$field = \intval($kamp->$field);
                    }
                }
            }
        }
    }

}