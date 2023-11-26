<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Table;

defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

class ProjectTable extends Table {

    public function __construct(DatabaseDriver $db) {
        parent::__construct('#__kampinfo_hitproject', 'id', $db);
    }

}