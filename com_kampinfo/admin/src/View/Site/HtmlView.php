<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\View\Site;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;

defined('_JEXEC') or die;

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

        $ids = array();
        $ids[] = $item->id;
        $this->canDo = KampInfoHelper::getActions('site', $ids);

        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar(): void {

        $isNew      = ($this->item->id == 0);
        $toolbar    = Toolbar::getInstance();

        ToolbarHelper::title($isNew ? 
            Text::_('COM_KAMPINFO_HITSITE_MANAGER_NEW') :
            Text::_('COM_KAMPINFO_HITSITE_MANAGER_EDIT'), 'kampinfo');

        $toolbar->apply('site.apply');
        $toolbar->save('site.save');
        $toolbar->cancel('site.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }

}
