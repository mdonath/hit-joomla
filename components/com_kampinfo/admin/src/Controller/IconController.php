<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Controller;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\MVC\Controller\FormController;


class IconController extends FormController {

    protected function allowAdd($data = []) {
        return $this->app->getIdentity()->authorise('hiticon.create', $this->option);
    }

    protected function allowEdit($data = [], $key = 'id') {
        return $this->app->getIdentity()->authorise('hiticon.edit', $this->option);
    }

}
