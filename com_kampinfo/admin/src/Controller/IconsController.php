<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Controller;

use Joomla\CMS\MVC\Controller\AdminController;

defined('_JEXEC') or die;

class IconsController extends AdminController {

    public function getModel($name = 'Icon', $prefix = 'Administrator', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

}
