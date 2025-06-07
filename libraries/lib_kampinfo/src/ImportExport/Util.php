<?php
namespace HITScoutingNL\Library\KampInfo\ImportExport;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\Table\Table;


class Util {

    public static function getHitTable($entity) {
        $table = Table::getInstance("Hit{$entity}Table", 'HITScoutingNL\\Library\\KampInfo\\ImportExport\\Table\\');
        if (!$table) {
            throw new GenericDataException("Table '{$entity}' not found!", 500);
        }
        return $table;
    }

}
