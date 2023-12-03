<?php

namespace HITScoutingNL\Component\KampInfoImExport\Administrator\Table;

use Joomla\Database\DatabaseDriver;

defined('_JEXEC') or die('Restricted access');

class HitKampTable extends AbstractHitTable {

    function __construct(DatabaseDriver $db) {
        parent::__construct('#__kampinfo_hitcamp', $db);
        $this->setColumnAlias('title', 'naam');
    }

}
