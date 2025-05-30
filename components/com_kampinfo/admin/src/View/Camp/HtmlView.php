<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\View\Camp;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;


class HtmlView extends BaseHtmlView {

    protected $form;
    protected $item;
    protected $state;
    protected $canDo;

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
            Text::_('COM_KAMPINFO_HITCAMP_MANAGER_NEW') :
            Text::_('COM_KAMPINFO_HITCAMP_MANAGER_EDIT'), 'kampinfo');

        // Button: Save
        $toolbar->apply('camp.apply');
        // Button: Save & Close
        $toolbar->save('camp.save');
        // Button: Preview
        $toolbar->preview("../index.php?option=com_kampinfo&view=activiteit&hitcamp_id={$this->item->id}", 'JGLOBAL_PREVIEW', true);
        // Button: Close
        $toolbar->cancel('camp.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }

}
