<?php

namespace HITScoutingNL\Library\KampInfo\Metadata;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Table\Extension;
use Joomla\Database\DatabaseInterface;
use Joomla\Registry\Registry;


class ManifestUtil {

    public static function getManifest(DatabaseInterface $db, string $extension, string $type = 'component') {
        $table = new Extension($db);
        $id = $table->find([
            'type' => $type,
            'element' => $extension
        ]);

        if (empty($id)) {
            return [];
        }

        $table->load($id);
        $registry = new Registry();
        $registry->loadString($table->manifest_cache);
        return $registry->toArray();
    }

}
