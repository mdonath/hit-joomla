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
	protected $canDo;

	function __construct($config = null) {
		parent :: __construct($config);
		$this->_addPath('template', $this->_basePath . '/views/default/tmpl');
	}

	function display($tpl = null) {

		// Get data from the model
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// What Access Permissions does this user have? What can (s)he do?
		$this->canDo = KampInfoHelper::getActions();

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
		if ($this->canDo->get($this->entityName.'.create')) {
			JToolBarHelper :: addNewX($this->entityName . '.add');
		}
		if ($this->canDo->get($this->entityName.'.edit')) {
			JToolBarHelper :: editListX($this->entityName . '.edit');
		}
			if ($this->canDo->get($this->entityName.'.delete')) {
			JToolBarHelper :: deleteListX('', $this->entityName . 's.delete');
		}
		if ($this->entityName == 'hitcamp') {
			if ($this->canDo->get($this->entityName.'.edit.state')) {
				JToolBarHelper :: divider();
				JToolBarHelper :: publish($this->entityName . 's.publish', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper :: unpublish($this->entityName . 's.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			}
		}
		JToolBarHelper :: divider();
		if (JFactory::getUser()->authorise('core.admin', 'com_kampinfo'))     {
			JToolBarHelper :: preferences('com_kampinfo');
		}
	}
	
}

abstract class KampInfoViewItemDefault extends JView {

	protected $entityName;
	protected $prefix;
	protected $canDo;

	public function display($tpl = null) {
		// get the Data
		$form = $this->get('Form');
		$item = $this->get('Item');

		// What Access Permissions does this user have? What can (s)he do?
		$this->canDo = KampInfoHelper::getActions($this->entityName, $this->item->id);

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
		if ($this->canDo->get($this->entityName . '.create') or $this->canDo->get($this->entityName . '.edit')) {
			JToolBarHelper :: apply($this->entityName . '.apply');
			JToolBarHelper :: save($this->entityName . '.save');
		}
		
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