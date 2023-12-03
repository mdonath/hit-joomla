<?php

namespace HITScoutingNL\Component\KampInfoImExport\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;

class InfoModel extends ListModel {

    public function getItems() {
        $table = Table::getInstance('extension');
        $id = $table->find(array(
            'type' => 'component',
            'element' => 'com_kampinfoimexport'
        ));
        if (!empty($id)) {
            $table->load($id);
            $registry = new Registry();
            $registry->loadString($table->manifest_cache);
            return $registry->toArray();
        }
        return [];
    }

}
