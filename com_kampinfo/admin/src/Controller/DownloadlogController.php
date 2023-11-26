<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;

class DownloadlogController extends FormController {

    protected $default_view = 'downloadlog';

    public function getModel($name = 'Downloadlog', $prefix = 'Administrator', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

}
