<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Controller;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\MVC\Controller\FormController;


class ProjectController extends FormController {

    protected function allowAdd($data = []) {
        return $this->app->getIdentity()->authorise('hitproject.create', $this->option);
    }

    protected function allowEdit($data = [], $key = 'id') {
        return $this->app->getIdentity()->authorise('hitproject.edit', $this->option);
    }

}
