<?php

namespace HITScoutingNL\Component\KampInfoImExport\Administrator\Table;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

defined('_JEXEC') or die('Restricted access');

abstract class AbstractHitTable extends Table {

    function __construct($table, DatabaseDriver $db) {
        parent::__construct($table, 'id', $db);
    }

    public function find($options = [], $sortOrder = 'naam') {
        $where = ['1=1'];

        foreach ($options as $col => $val) {
            $where[] = $col . ' = ' . $this->getDbo()->quote($val);
        }

        $query = $this->getDbo()->getQuery(true)
            -> select('*')
            -> from($this->getDbo()->quoteName($this->getTableName()))
            -> where(implode(' AND ', $where))
            -> order($sortOrder . ' ASC')
        ;

        $this->getDbo()->setQuery($query);

        return $this->getDbo()->loadObjectList();
    }

}
