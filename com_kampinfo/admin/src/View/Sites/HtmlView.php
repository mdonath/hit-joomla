<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\View\Sites;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;

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

        $this->authoriseItems($this->items);

        $this->addToolbar();

        parent::display($tpl);
    }

    protected function authoriseItems($items) {
        $ids = [];
        if ($items) {
            foreach ($items as $row) {
                $ids[] = $row->id;
            }
        }
        // What Access Permissions does this user have? What can (s)he do?
        $this->canDo = KampInfoHelper::getActions('site', $ids);
    }

    protected function addToolbar() {
        $user = Factory::getApplication()->getIdentity();
        $toolbar = Toolbar::getInstance();

        ToolbarHelper::title(Text::_('COM_KAMPINFO_SUBMENU_HITSITES'), 'kampinfo');

        if ($this->canDo->get('hitsite.create')) {
            $toolbar->addNew('site.add');
        }

        if ($this->canDo->get('hitsite.edit')) {
            $toolbar
                ->edit('site.edit')
                ->listCheck(true);
        }

        if ($this->canDo->get('hitsite.delete')) {
            $toolbar
                ->delete('sites.delete')
                ->message('JGLOBAL_CONFIRM_DELETE')
                ->listCheck(true);
        }

        if (!$this->isEmptyState && ($this->canDo->get('hitsite.edit') || $this->canDo->get('hitsite.edit.state') )) {
            $dropdown = $toolbar
                ->dropdownButton('status-group', 'JTOOLBAR_CHANGE_STATUS')
                ->toggleSplit(false)
                ->icon('icon-ellipsis-h')
                ->buttonClass('btn btn-action')
                ->listCheck(true);
            
            $childBar = $dropdown->getChildToolbar();

            if ($this->canDo->get('hitsite.edit')) {
                $childBar->standardButton('publish', 'Akkoord', 'sites.akkoordPlaats')
                    ->listCheck(true);
                $childBar->standardButton('unpublish', 'Niet akkoord', 'sites.nietAkkoordPlaats')
                    ->listCheck(true);
            }

            if ($this->canDo->get('hitsite.edit.state')) {
                $childBar
                    ->publish('sites.publish')
                    ->listCheck(true);
                $childBar
                    ->unpublish('sites.unpublish')
                    ->listCheck(true);
            }

        }

        if ($this->canDo->get('core.admin') || $this->canDo->get('core.options')) {
            $toolbar->preferences('com_kampinfo');
        }

    }
}
