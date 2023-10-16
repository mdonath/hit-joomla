<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Controller;

use Joomla\CMS\MVC\Controller\FormController;

defined('_JEXEC') or die;

class ProjectController extends FormController {

    protected function allowAdd($data = []) {
        return $this->app->getIdentity()->authorise('hitproject.create', $this->option);
    }

    protected function allowEdit($data = [], $key = 'id') {
        return $this->app->getIdentity()->authorise('hitproject.edit', $this->option);
    }

}
