<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\View\Projects;

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
        $toolbar = Toolbar::getInstance();

        ToolbarHelper::title(Text::_('COM_KAMPINFO_SUBMENU_HITPROJECTS'), 'kampinfo');

        if ($canDo->get('hitproject.create')) {
            $toolbar->addNew('project.add');
        }

        if ($canDo->get('hitproject.edit')) {
            $toolbar
                -> edit('project.edit')
                -> listCheck(true);
            $toolbar->divider();
        }

        if ($canDo->get('hitproject.delete')) {
            $toolbar
                -> delete('projects.delete')
                -> message('JGLOBAL_CONFIRM_DELETE')
                -> listCheck(true);
            $toolbar->divider();
        }

        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            $toolbar->preferences('com_kampinfo');
            $toolbar->divider();
        }

    }
}
