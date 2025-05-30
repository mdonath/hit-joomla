<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\View\Camps;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;


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

    protected function addToolbar():void  {
        $toolbar = $this->getDocument()->getToolbar();

        ToolbarHelper::title(Text::_('COM_KAMPINFO_HITCAMPS_DOCTITLE'), 'kampinfo');

        $canDo = ContentHelper::getActions('com_kampinfo', 'camp', $this->state->get('filter.plaats'));

        echo("<ul>");
        foreach ($canDo as $k=>$v) {
            echo ("<li>Action: $k: $v</li>");
        }
        echo('</ul>');

        // Button - New
        if ($canDo->get('hitcamp.create')) {
            $toolbar->addNew('camp.add');
        }

        // Button - Edit
        if ($canDo->get('hitcamp.edit')) {
            $toolbar
                ->edit('camp.edit')
                ->listCheck(true);
        }

        // Button - Delete
        if ($canDo->get('hitcamp.delete')) {
            $toolbar
                ->delete('camps.delete')
                ->message('JGLOBAL_CONFIRM_DELETE')
                ->listCheck(true);
        }

        // Dropdown - Actions
        if (!$this->isEmptyState && ($canDo->get('hitsite.edit') || $canDo->get('hitcamp.edit') || $canDo->get('hitcamp.edit.state'))) {
            $dropdown = $toolbar
                ->dropdownButton('status-group', 'JTOOLBAR_CHANGE_STATUS')
                ->toggleSplit(false)
                ->icon('icon-ellipsis-h')
                ->buttonClass('btn btn-action')
                ->listCheck(true);
        
            $childBar = $dropdown->getChildToolbar();

            // Dropdown Item - 'Publish' & 'Unpublish'
            if ($canDo->get('hitcamp.edit.state')) {
                $childBar
                    ->publish('camps.publish')
                    ->listCheck(true);
                $childBar
                    ->unpublish('camps.unpublish')
                    ->listCheck(true);
            }

            // Dropdown Item: 'Akkoord Kamp' & 'Niet akkoord kamp'
            if ($canDo->get('hitcamp.edit')) {
                $childBar->standardButton('publish', 'Akkoord kamp', 'camps.akkoordKamp')
                    ->listCheck(true);
                $childBar->standardButton('unpublish', 'Niet akkoord kamp', 'camps.nietAkkoordKamp')
                    ->listCheck(true);
            }

            // Dropdown Item: 'Akkoord plaats' & 'Niet akkoord plaats'
            if ($canDo->get('hitsite.edit')) {
                $childBar->standardButton('publish', 'Akkoord plaats', 'camps.akkoordPlaats')
                    ->listCheck(true);
                $childBar->standardButton('unpublish', 'Niet akkoord plaats', 'camps.nietAkkoordPlaats')
                    ->listCheck(true);
            }
        }

        if ($canDo->get('core.admin', 'com_kampinfo') || $canDo->get('core.options', 'com_kampinfo')) {
            $toolbar->preferences('com_kampinfo');
            $toolbar->divider();
        }
    }

}
