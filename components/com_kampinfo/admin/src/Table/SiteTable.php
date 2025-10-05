<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Table;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\Access\Rules;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Asset;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\ParameterType;
use Joomla\Event\DispatcherInterface;
use Joomla\Utilities\ArrayHelper;

use HITScoutingNL\Library\KampInfo\Helper\KampInfoHelper;


class SiteTable extends Table {

    public function __construct(DatabaseInterface $db, ?DispatcherInterface $dispatcher = null) {
        parent::__construct('#__kampinfo_hitsite', 'id', $db, $dispatcher);
    }

    public function bind($array, $ignore = '') {
        // Bind the rules.
        if (isset($array['rules']) && is_array($array['rules'])) {
            $rules = new Rules($array['rules']);
            $this->setRules($rules);
        }
        return parent::bind($array, $ignore);
    }

    protected function _getAssetName() {
        $k = $this->_tbl_key;
        $id = (int) $this->$k;
        return 'com_kampinfo.site.'.$id;
    }

    protected function _getAssetTitle() {
        return $this->naam;
    }

    protected function _getAssetParentId(Table $table = NULL, $id = NULL) {
        // We will retrieve the parent-asset from the Asset-table
        $assetParent = new Asset($this->getDbo());
        // Default: if no asset-parent can be found we take the global asset
        $assetParentId = $assetParent->getRootId();

        // The item has the component as asset-parent
        $assetParent->loadByName('com_kampinfo');

        // Return the found asset-parent-id
        if ($assetParent->id) {
            $assetParentId = $assetParent->id;
        }
        return $assetParentId;
    }

    public function akkoordPlaats($pks = null, $state = 1) {
        $k = $this->_tbl_key;

        // Sanitize input.
        $pks    = ArrayHelper::toInteger($pks);
        $state  = (int) $state;

        // If there are no primary keys set check to see if the instance key is set.
        if (empty($pks)) {
            if ($this->$k) {
                $pks = [$this->$k];
            } else {
                // Nothing to set publishing state on, return false.
                $this->setError(Text::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));

                return false;
            }
        }

        // Get an instance of the table
        $table = new SiteTable($this->getDbo());

        // For all keys
        foreach ($pks as $pk) {
            // Load the site
            if (!$table->load($pk)) {
                $this->setError($table->getError());
            }

            $table->akkoordHitPlaats = $state;
            $table->check();
            if (!$table->store()) {
                $this->setError($table->getError());
            }
        }

        return \count($this->getErrors()) == 0;
    }

    public function copyKampen($pks) {
        $aantalVerwerktePlaatsen = 0;
        foreach ($pks as $pk) {
            if ($this->copyKampenVoorPlaats($pk)) {
                $aantalVerwerktePlaatsen = $aantalVerwerktePlaatsen + 1;
            }
        }
        return $aantalVerwerktePlaatsen;
    }

    private function copyKampenVoorPlaats($siteId) {
        $app = Factory::getApplication();
        // Get an instance of the table
        $db = $this->getDbo();
        $table = new SiteTable($db);

        // Haal de HIT plaats op
        if (!$table->load($siteId)) {
            $this->setError($table->getError());
        }

        $plaatsNaam = $table->naam;

        // Als de plaats er vorig jaar nog niet was -> stop
        $plaatsIdVorigJaar = $this->getHitPlaatsIdVanVorigJaar($db, $siteId);
        if (empty($plaatsIdVorigJaar)) {
            $app->enqueueMessage("HIT {$plaatsNaam} bestond vorig jaar nog niet! Er is niets gekopieerd.", 'error');
            return false;
        }

        // Heeft de plaats voor dit jaar al kamponderdelen -> stop
        if ($this->getKampenVanPlaats($db, $siteId)) {
            $app->enqueueMessage("HIT {$plaatsNaam} heeft voor dit jaar al kamponderdelen in KampInfo! Er is niets gekopieerd.", 'error');
            return false;
        }

        // Als er vorig jaar geen kamponderdelen waren (maar wel een plaats?) -> stop
        $teCopierenKampen = $this->getKampenVanPlaats($db, $plaatsIdVorigJaar);
        if (empty($teCopierenKampen)) {
            $app->enqueueMessage('HIT '.$plaatsNaam . ' had vorig jaar geen kamponderdelen! Er is niets gekopieerd.', 'error');
            return false;
        }

        $namen = [];
        foreach ($teCopierenKampen as $row) {
            $kamp = new CampTable($db);
            if ($kamp->load($row->id)) {
                $namen[] = $kamp->naam;

                // Maak er een nieuw kamp van
                $kamp->id = null;
                // Zet over naar nieuwe plaats
                $kamp->hitsite_id = $siteId;
                // Reset inschrijfgegevens
                $kamp->shantiFormuliernummer = null;
                $kamp->ouderShantiFormuliernummer = null;
                $kamp->extraShantiFormuliernummer = null;
                $kamp->aantalSubgroepen = 0;
                $kamp->aantalDeelnemers = 0;
                $kamp->gereserveerd = 0;
                // Reset akkoord, gepubliceerd en geannuleerd
                $kamp->akkoordHitKamp = 0;
                $kamp->akkoordHitPlaats = 0;
                $kamp->published = 0;
                $kamp->geannuleerd = 0;
                // Zet maximum op het oorspronkelijke maximum aantal en niet het huidige aantal van vorig jaar
                $kamp->maximumAantalDeelnemers = $kamp->maximumAantalDeelnemersOrigineel;
                // Zet startdag over naar dit jaar
                $kamp->startDatumTijd = KampInfoHelper::herberekenDatum($kamp->startDatumTijd);
                $kamp->eindDatumTijd = KampInfoHelper::herberekenDatum($kamp->eindDatumTijd);
                
                $kamp->store();
            }
        }

        $aantalKampenOvergezet = \count($namen);
        $app->enqueueMessage("Bij HIT {$plaatsNaam} zijn {$aantalKampenOvergezet} kampen overgezet!");
        $app->enqueueMessage("'" . implode("', '", $namen) . "'");
        return true;
    }

    private function getHitPlaatsIdVanVorigJaar($db, int $siteId) {
        $query = $db->getQuery(true)
            ->select($db->quoteName('s_vorig.id'))
            ->from($db->quoteName('#__kampinfo_hitsite', 's_now'))
            ->join('LEFT',
                $db->quoteName('#__kampinfo_hitproject', 'p_now'),
                $db->quoteName('s_now.hitproject_id') .' = '. $db->quoteName('p_now.id')
            )
            ->join('LEFT',
                $db->quoteName('#__kampinfo_hitproject', 'p_vorig'),
                $db->quoteName('p_now.jaar') .' - 1 = '. $db->quoteName('p_vorig.jaar')
            )
            ->join('LEFT',
                $db->quoteName('#__kampinfo_hitsite', 's_vorig'),
                '(' .
                    $db->quoteName('p_vorig.id') .' = '. $db->quoteName('s_vorig.hitproject_id') .
                    ' AND ' .
                    $db->quoteName('s_now.naam') .' = '. $db->quoteName('s_vorig.naam') .
                ')'
            )
            ->where($db->quoteName('s_now.id') .' = :siteId')
            ->bind(':siteId', $siteId, ParameterType::INTEGER);

        $db->setQuery($query);

        return $db->loadResult();
    }

    private function getKampenVanPlaats($db, $siteId) {
        $query = $db->getQuery(true)
        ->select($db->quoteName('c.id'))
        ->from($db->quoteName('#__kampinfo_hitcamp', 'c'))
        ->where($db->quoteName('c.hitsite_id') .' = :siteId')
        ->bind(':siteId', $siteId, ParameterType::INTEGER);

        $db->setQuery($query);

        return $db->loadObjectList();
    }
}
