<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\View\Info;

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

defined('_JEXEC') or die;

class HtmlView extends BaseHtmlView {
    
    function display($tpl = null) {
        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar() {
        $canDo = ContentHelper::getActions('com_kampinfo');

        ToolbarHelper::title(Text::_('COM_KAMPINFO_INFO_DOCTITLE'), 'kampinfo');

        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            $toolbar = Toolbar::getInstance();
            $toolbar->preferences('com_kampinfo');
        }
    }
}
