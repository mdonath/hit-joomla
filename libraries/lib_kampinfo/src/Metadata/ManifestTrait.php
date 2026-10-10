<?php

namespace HITScoutingNL\Library\KampInfo\Metadata;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\Database\DatabaseAwareTrait;
use Joomla\CMS\Table\Extension;
use Joomla\Registry\Registry;

trait ManifestTrait {

    use DatabaseAwareTrait;

    /**
     * Gets the manifest data for a given extension.
     *
     * @param string $extension The extension name (e.g., 'com_example').
     * @param string $type The extension type (e.g., 'component').
     *
     * @return array The manifest data as an associative array.
     */
    public function getManifestData(string $extension, string $type = 'component'): array {
        $table = new Extension($this->getDatabase());
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
