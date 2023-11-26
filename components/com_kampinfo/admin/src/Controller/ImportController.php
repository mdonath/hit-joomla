<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Controller;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;


class ImportController extends FormController {

    protected $default_view = 'import';

    public function getModel($name = 'Import', $prefix = 'Administrator', $config = array('ignore_request' => true)) {
        return parent::getModel($name, $prefix, $config);
    }

    public function importKampgegevens() {
        $model = $this->getModel();

        $model->importKampgegevens();

        $this->setRedirect(Route::_('index.php?option=com_kampinfo&view=import', false));
        return true;
    }

}
