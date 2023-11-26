<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\View\Import;

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

defined('_JEXEC') or die;

class HtmlView extends BaseHtmlView {

    protected $form;

    function display($tpl = null) {
        $this->form  = $this->get('Form');
        // $this->item  = $this->get('Item');

        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar() {
        ToolbarHelper::title(Text::_('COM_KAMPINFO_IMPORT_DOCTITLE'), 'kampinfo');

        $canDo = ContentHelper::getActions('com_kampinfo');
        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            $toolbar = Toolbar::getInstance();
            $toolbar->preferences('com_kampinfo');
        }
    }
}
