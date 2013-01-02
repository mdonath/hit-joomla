<?php


// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Default View voor lijsten.
 */
abstract class KampInfoViewListDefault extends JViewLegacy {

	protected $items;
	protected $pagination;
	protected $state;

	protected $toolbarTitle;
	protected $entityName;

	function __construct($config = null) {
		parent :: __construct($config);
		$this->_addPath('template', $this->_basePath . '/views/default/tmpl');
	}

	function display($tpl = null) {

		// Get data from the model
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors
		if (count($errors = $this->get('Errors'))) {
			JError :: raiseError(500, implode('<br />', $errors));
			return false;
		}

		// Set the toolbar
		$this->addToolBar();

		// Display the template
		parent :: display($tpl);
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper :: title(JText :: _($this->toolbarTitle), 'kampinfo');

		JToolBarHelper :: addNewX($this->entityName . '.add');
		JToolBarHelper :: editListX($this->entityName . '.edit');
		JToolBarHelper :: divider();
		JToolBarHelper :: deleteListX('', $this->entityName . 's.delete');
		if (JFactory::getUser()->authorise('core.admin', 'com_helloworld'))     {
			JToolBarHelper :: preferences('com_kampinfo');
		}
	}
	
}

abstract class KampInfoViewItemDefault extends JView {

	protected $entityName;
	protected $prefix;

	public function display($tpl = null) {
		// get the Data
		$form = $this->get('Form');
		$item = $this->get('Item');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError :: raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign the Data
		$this->form = $form;
		$this->item = $item;

		// Set the toolbar
		$this->addToolBar();

		// Display the template
		parent :: display($tpl);

		// Set the document
		$this->setDocument();
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() {
		$input = JFactory :: getApplication()->input;
		$input->set('hidemainmenu', true);
		JToolBarHelper :: title(JText :: _($this->prefix . ($this->isNieuwItem() ? '_MANAGER_NEW' : '_MANAGER_EDIT')), 'kampinfo');
		JToolBarHelper :: save($this->entityName . '.save');
		JToolBarHelper :: cancel($this->entityName .'.cancel', $this->isNieuwItem() ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
	}

	/**
     * Method to set up the document properties.
     */
	protected function setDocument() {
		$document = JFactory :: getDocument();
		$document->setTitle(JText :: _($this->prefix . ($this->isNieuwItem() ? '_CREATING' : '_EDITING')));
	}

	protected function isNieuwItem() {
		return ($this->item->id == 0);	
	}

}