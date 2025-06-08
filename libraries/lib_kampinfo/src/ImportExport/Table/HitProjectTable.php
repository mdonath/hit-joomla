<?php

namespace HITScoutingNL\Library\KampInfo\ImportExport\Table;

defined('_JEXEC') or die('Restricted access');

use Joomla\Database\DatabaseInterface;
use Joomla\Event\DispatcherInterface;


class HitProjectTable extends AbstractHitTable {

    function __construct(DatabaseInterface $db, ?DispatcherInterface $dispatcher = null) {
        parent::__construct('#__kampinfo_hitproject', $db, $dispatcher);
        $this->setColumnAlias('title', 'naam');
    }

    public function find($options = [], $sortOrder = 'jaar') {
        return parent::find($options, $sortOrder);
    }

}
