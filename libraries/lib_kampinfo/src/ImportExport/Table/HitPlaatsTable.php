<?php

namespace HITScoutingNL\Library\KampInfo\ImportExport\Table;

use Joomla\Database\DatabaseDriver;

defined('_JEXEC') or die('Restricted access');

class HitPlaatsTable extends AbstractHitTable {

    function __construct(DatabaseDriver $db) {
        parent::__construct('#__kampinfo_hitsite', $db);
        $this->setColumnAlias('title', 'naam');
    }

}
