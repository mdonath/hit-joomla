<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\View\Sites;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

use HITScoutingNL\Library\KampInfo\Helper\KampInfoHelper;


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

    protected function addToolbar() {
        $canDo = ContentHelper::getActions('com_kampinfo', 'site');
        $user    = $this->getCurrentUser();
        $toolbar = $this->getDocument()->getToolbar();

        ToolbarHelper::title(Text::_('COM_KAMPINFO_HITSITES_DOCTITLE'), 'kampinfo');

        if ($canDo->get('hitsite.create')) {
            $toolbar->addNew('site.add');
        }

        if ($canDo->get('hitsite.edit')) {
            $toolbar
                ->edit('site.edit')
                ->listCheck(true);
        }

        if ($canDo->get('hitsite.delete')) {
            $toolbar
                ->delete('sites.delete')
                ->message('JGLOBAL_CONFIRM_DELETE')
                ->listCheck(true);
        }

        if (!$this->isEmptyState && ($canDo->get('hitsite.edit') || $canDo->get('hitsite.edit.state') )) {
            $dropdown = $toolbar
                ->dropdownButton('status-group', 'JTOOLBAR_CHANGE_STATUS')
                ->toggleSplit(false)
                ->icon('icon-ellipsis-h')
                ->buttonClass('btn btn-action')
                ->listCheck(true);
            
            $childBar = $dropdown->getChildToolbar();

            if ($canDo->get('hitsite.edit')) {
                $childBar->standardButton('publish', 'Akkoord', 'sites.akkoordPlaats')
                    ->listCheck(true);
                $childBar->standardButton('unpublish', 'Niet akkoord', 'sites.nietAkkoordPlaats')
                    ->listCheck(true);
            }

            if ($canDo->get('hitsite.edit.state')) {
                $childBar
                    ->publish('sites.publish')
                    ->listCheck(true);
                $childBar
                    ->unpublish('sites.unpublish')
                    ->listCheck(true);
            }

        }

        if ($user->authorise('core.admin', 'com_kampinfo') || $user->authorise('core.options', 'com_kampinfo')) {
            $toolbar->preferences('com_kampinfo');
        }

    }
}
