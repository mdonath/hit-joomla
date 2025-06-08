<?php

namespace HITScoutingNL\Library\KampInfo\ImportExport\Table;

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseInterface;
use Joomla\Event\DispatcherInterface;


abstract class AbstractHitTable extends Table {

    function __construct($table, DatabaseInterface $db, ?DispatcherInterface $dispatcher = null) {
        parent::__construct($table, 'id', $db, $dispatcher);
    }

    public function find($options = [], $sortOrder = 'naam') {
        $db = $this->getDbo();
        $where = ['1=1'];

        foreach ($options as $col => $val) {
            $where[] = $db->quoteName($col) . ' = ' . $db->quote($val);
        }

        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName($this->getTableName()))
            ->where(implode(' AND ', $where))
            ->order($db->quoteName($sortOrder) . ' ASC')
        ;

        $db->setQuery($query);

        return $db->loadObjectList();
    }

}
