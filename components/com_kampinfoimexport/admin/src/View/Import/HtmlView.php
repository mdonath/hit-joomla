<?php

namespace HITScoutingNL\Component\KampInfoImExport\Administrator\View\Import;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

class HtmlView extends BaseHtmlView {

    function display($tpl = null) {
        ToolbarHelper::title("KampInfo Import");

        parent::display($tpl);
    }

}
