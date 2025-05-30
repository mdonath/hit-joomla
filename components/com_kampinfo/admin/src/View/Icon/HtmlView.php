<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\View\Icon;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;


class HtmlView extends BaseHtmlView {

    protected $form;
    protected $item;
    protected $state;

    public function display($tpl = null): void {
        $model       = $this->getModel();
        $this->form  = $model->getForm();
        $this->item  = $model->getItem();
        $this->state = $model->getState();

        // Check for errors.
        if (\count($errors = $this->get('Errors'))) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar(): void {
        $isNew      = ($this->item->id == 0);
        $toolbar    = $this->getDocument()->getToolbar();

        ToolbarHelper::title($isNew ?
            Text::_('COM_KAMPINFO_HITICON_MANAGER_NEW') :
            Text::_('COM_KAMPINFO_HITICON_MANAGER_EDIT'), 'kampinfo');

        // Button: Save
        $toolbar->apply('icon.apply');
        // Button: Save & Close
        $toolbar->save('icon.save');
        // Button: Close
        $toolbar->cancel('icon.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }

}
