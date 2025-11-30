<?php
namespace HITScoutingNL\Library\KampInfo\ImportExport;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\Database\DatabaseInterface;


class Util {

    public static function getHitTable(string $entity) {
        $db = Factory::getContainer()->get(DatabaseInterface::class);
        $tableName = "HITScoutingNL\\Library\\KampInfo\\ImportExport\\Table\\Hit{$entity}Table";
        return new $tableName($db);
    }

}
