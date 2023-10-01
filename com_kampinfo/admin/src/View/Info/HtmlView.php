<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\View\Info;

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

defined('_JEXEC') or die;

class HtmlView extends BaseHtmlView {
    
    function display($tpl = null) {
        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar() {
        ToolbarHelper::title(Text::_('COM_KAMPINFO_INFO_DOCTITLE'), 'kampinfo');
    }
}
