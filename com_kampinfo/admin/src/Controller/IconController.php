<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Controller;

use Joomla\CMS\MVC\Controller\FormController;

defined('_JEXEC') or die;

class IconController extends FormController {

    protected function allowAdd($data = []) {
        return $this->app->getIdentity()->authorise('hiticon.create', $this->option);
    }

    protected function allowEdit($data = [], $key = 'id') {
        return $this->app->getIdentity()->authorise('hiticon.edit', $this->option);
    }

}
