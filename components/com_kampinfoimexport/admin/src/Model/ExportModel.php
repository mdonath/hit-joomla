<?php

namespace HITScoutingNL\Component\KampInfoImExport\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Table\Table;

use HITScoutingNL\Library\KampInfo\ImportExport\KampInfoExporter;


class ExportModel extends ListModel {

    public function getItems() {
        $input = Factory::getApplication()->getInput();
        $jaar = $input->getInt('jaar', 0);

        $items = [];
        try {
            $exporter = new KampInfoExporter();

            $items = $exporter->exportAlles($jaar);
        } catch (GenericDataException $e) {
            $app->enqueueMessage("Error {$e}");
        }

        return $items;
    }

}
