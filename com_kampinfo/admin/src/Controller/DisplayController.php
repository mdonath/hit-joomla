<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

class DisplayController extends BaseController {

    protected $default_view = 'info';
    
    public function display($cachable = false, $urlparams = []) {
        return parent::display($cachable, $urlparams);
    }
    
}
