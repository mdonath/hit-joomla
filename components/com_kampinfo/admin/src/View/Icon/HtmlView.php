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
        $this->form  = $this->get('Form');
        $this->item  = $this->get('Item');
        $this->state = $this->get('State');

        // Check for errors.
        if (\count($errors = $this->get('Errors'))) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar(): void {
        $isNew      = ($this->item->id == 0);
        $toolbar    = Toolbar::getInstance();

        ToolbarHelper::title($isNew ?
            Text::_('COM_KAMPINFO_HITICON_MANAGER_NEW') :
            Text::_('COM_KAMPINFO_HITICON_MANAGER_EDIT'), 'kampinfo');

        $toolbar->apply('icon.apply');
        $toolbar->save('icon.save');
        $toolbar->cancel('icon.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }

}
