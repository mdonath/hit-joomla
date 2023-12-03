<?php

namespace HITScoutingNL\Component\KampInfoImExport\Administrator\Table;

use Joomla\Database\DatabaseDriver;

defined('_JEXEC') or die('Restricted access');

class HitProjectTable extends AbstractHitTable {

    function __construct(DatabaseDriver $db) {
        parent::__construct('#__kampinfo_hitproject', $db);
        $this->setColumnAlias('title', 'naam');
    }

    public function find($options = [], $sortOrder = 'jaar') {
        return parent::find($options, $sortOrder);
    }

}
