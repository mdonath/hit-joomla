<?php

namespace HITScoutingNL\Component\HitDownloads\Administrator\View\Info;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;


class HtmlView extends BaseHtmlView {

    function display($tpl = null) {
        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar() {
        $user    = $this->getCurrentUser();
        $toolbar = $this->getDocument()->getToolbar();

        ToolbarHelper::title(Text::_('COM_HITDOWNLOADS_INFO_DOCTITLE'), 'hitdownloads');

        if ($user->authorise('core.admin', 'com_hitdownloads') || $user->authorise('core.options', 'com_hitdownloads')) {
            $toolbar->preferences('com_hitdownloads');
        }
    }
}
