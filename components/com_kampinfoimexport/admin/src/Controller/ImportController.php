<?php

namespace HITScoutingNL\Component\KampInfoImExport\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController;

class ImportController extends AdminController {

    public function getModel($name = 'Import', $prefix = 'Administrator', $config = array('ignore_request' => true)) {
        return parent::getModel($name, $prefix, $config);
    }

    public function importAlles() {
        // Check for request forgeries
        $this->checkToken();

        $model = $this->getModel();
        $model->importAlles();
    }


    public function importEenPlaats() {
        // Check for request forgeries
        $this->checkToken();

        $model = $this->getModel();
        $model->importEenPlaats();
    }

}
