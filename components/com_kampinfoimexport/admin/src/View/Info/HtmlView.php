<?php

namespace HITScoutingNL\Component\KampInfoImExport\Administrator\View\Info;

use Joomla\CMS\Toolbar\ToolbarHelper;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView {

    function display($tpl = null) {
        ToolbarHelper::title("Info");

        parent::display($tpl);
    }

}
