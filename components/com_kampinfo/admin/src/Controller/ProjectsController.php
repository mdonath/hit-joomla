<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Controller;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\MVC\Controller\AdminController;


class ProjectsController extends AdminController {

    public function getModel($name = 'Project', $prefix = 'Administrator', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

}
