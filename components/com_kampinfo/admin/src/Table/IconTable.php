<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Table;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseInterface;
use Joomla\Event\DispatcherInterface;


class IconTable extends Table {

    public function __construct(DatabaseInterface $db, ?DispatcherInterface $dispatcher = null) {
        parent::__construct('#__kampinfo_hiticon', 'id', $db, $dispatcher);
    }

}
