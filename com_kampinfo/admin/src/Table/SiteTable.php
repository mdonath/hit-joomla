<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Table;

defined('_JEXEC') or die;

use Joomla\CMS\Access\Rules;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
use Joomla\Utilities\ArrayHelper;

class SiteTable extends Table {

    public function __construct(DatabaseDriver $db) {
        parent::__construct('#__kampinfo_hitsite', 'id', $db);
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
        $assetParent = Table::getInstance('Asset');
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
        $table = Table::getInstance('SiteTable', __NAMESPACE__ . '\\', ['dbo' => $this->_db]);

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

}
