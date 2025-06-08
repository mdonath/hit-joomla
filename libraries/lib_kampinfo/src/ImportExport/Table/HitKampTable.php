<?php

namespace HITScoutingNL\Library\KampInfo\ImportExport\Table;

defined('_JEXEC') or die('Restricted access');

use Joomla\Database\DatabaseInterface;
use Joomla\Event\DispatcherInterface;


class HitKampTable extends AbstractHitTable {

    function __construct(DatabaseInterface $db, ?DispatcherInterface $dispatcher = null) {
        parent::__construct('#__kampinfo_hitcamp', $db, $dispatcher);
        $this->setColumnAlias('title', 'naam');
    }

}
