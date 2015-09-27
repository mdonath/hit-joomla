<?php


// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Import View.
*/
class KampInfoViewImport extends JViewLegacy {

	public function display($tpl = null) {
		$form = $this->get('Form');
		$item = $this->get('Item');

		$this->form = $form;
		$this->item=$item;

		JToolBarHelper::title('Import', 'kampinfo');
		if (JFactory::getUser()->authorise('core.admin', 'com_kampinfo'))     {
			JToolBarHelper::preferences('com_kampinfo');
		}
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

}