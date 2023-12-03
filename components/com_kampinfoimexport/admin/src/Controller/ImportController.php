<?php

namespace HITScoutingNL\Component\KampInfoImExport\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

class ImportController extends BaseController {

    protected $default_view = 'import';

    public function display($cachable = false, $urlparams = []) {
        return parent::display($cachable, $urlparams);
    }

    public function importAlles() {
        $this->checkToken();
        $model = $this->getModel('import');
        $model->importAlles();
        return true;
    }

    public function importEenPlaats() {
        $this->checkToken();
        $model = $this->getModel('import');
        $model->importEenPlaats();
        return true;
    }

}
