<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\View\Camps;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

defined('_JEXEC') or die;

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

        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar() {
        $canDo   = ContentHelper::getActions('com_kampinfo');
        $user    = Factory::getApplication()->getIdentity();
        $toolbar = Toolbar::getInstance();

        ToolbarHelper::title(Text::_('COM_KAMPINFO_SUBMENU_HITCAMPS'), 'kampinfo');

        //if ($canDo->get('core.create')) {
            $toolbar->addNew('camp.add');
        //}

        $toolbar->edit('camp.edit')
            ->listCheck(true);
        $toolbar->divider();

        // if ($canDo->get('core.delete')) {
            $toolbar->delete('camps.delete')
                ->message('JGLOBAL_CONFIRM_DELETE')
                ->listCheck(true);
            $toolbar->divider();
        // }

        if ($canDo->get('core.admin') || $canDo->get('core.options')) {
            $toolbar->preferences('com_kampinfo');
            $toolbar->divider();
        }

    }
}
