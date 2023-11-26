<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\View\Icons;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;


class HtmlView extends BaseHtmlView {

    protected $items = [];
    protected $state;

    public $filterForm;

    public $pagination;

    private $isEmptyState = false;

    function display($tpl = null): void {
        $this->items         = $this->get('Items');
        $this->state         = $this->get('State');
        $this->filterForm    = $this->get('FilterForm');
        $this->pagination    = $this->get('Pagination');
        $this->activeFilters = $this->get('ActiveFilters');

        if (!\count($this->items) && $this->isEmptyState = $this->get('IsEmptyState')) {
            $this->setLayout('emptystate');
        }

        $this->canDo = ContentHelper::getActions('com_kampinfo');

        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar() {
        $canDo   = $this->canDo;
        $user    = Factory::getApplication()->getIdentity();
        $toolbar = Toolbar::getInstance();

        ToolbarHelper::title(Text::_('COM_KAMPINFO_SUBMENU_HITICONS'), 'kampinfo');

        if ($canDo->get('hiticon.create')) {
            $toolbar->addNew('icon.add');
        }

        if ($this->canDo->get('hiticon.edit')) {
            $toolbar
                ->edit('icon.edit')
                ->listCheck(true);
        }

        if ($canDo->get('hiticon.delete')) {
            $toolbar
                ->delete('icons.delete')
                ->message('JGLOBAL_CONFIRM_DELETE')
                ->listCheck(true);
        }

        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            $toolbar->preferences('com_kampinfo');
        }
    }

}
