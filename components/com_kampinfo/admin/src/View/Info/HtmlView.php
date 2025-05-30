<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\View\Info;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;


class HtmlView extends BaseHtmlView {

    function display($tpl = null) {
        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar() {
        $canDo   = ContentHelper::getActions('com_kampinfo');
        $user    = $this->getCurrentUser();
        $toolbar = $this->getDocument()->getToolbar();

        ToolbarHelper::title(Text::_('COM_KAMPINFO_INFO_DOCTITLE'), 'kampinfo');

        if ($user->authorise('core.admin', 'com_kampinfo') || $user->authorise('core.options', 'com_kampinfo')) {
            $toolbar->preferences('com_kampinfo');
        }
    }
}
