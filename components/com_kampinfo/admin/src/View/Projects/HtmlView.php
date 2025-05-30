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

    public $filterForm;
    public $activeFilters = [];

    protected $items = [];
    protected $state;
    protected $pagination;

    private $isEmptyState = false;

    function display($tpl = null): void {
        $model               = $this->getModel();
        $this->items         = $model->getItems();
        $this->state         = $model->getState();
        $this->filterForm    = $model->getFilterForm();
        $this->pagination    = $model->getPagination();
        $this->activeFilters = $model->getActiveFilters();

        if (!\count($this->items) && $this->isEmptyState = $this->get('IsEmptyState')) {
            $this->setLayout('emptystate');
        }

        // Check for errors.
        if (\count($errors = $model->getErrors())) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar(): void {
        $toolbar = $this->getDocument()->getToolbar();

        ToolbarHelper::title(Text::_('COM_KAMPINFO_HITPROJECTS_DOCTITLE'), 'kampinfo');

        $canDo = ContentHelper::getActions('com_kampinfo', 'project');

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
