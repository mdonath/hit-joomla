<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Table;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;


class IconTable extends Table {

    public function __construct(DatabaseDriver $db) {
        parent::__construct('#__kampinfo_hiticon', 'id', $db);
    }

}
