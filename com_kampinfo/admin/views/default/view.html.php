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

		$this->authoriseItems($this->items);
		
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

	protected function authoriseItems($items) {
		$ids = array();
		if ($items) {
			foreach ($items as $row) {
				$ids[] = $row->id;
			}
		}
		// What Access Permissions does this user have? What can (s)he do?
		$this->canDo = KampInfoHelper::getActions($this->entityName, $ids);
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper :: title(JText :: _($this->toolbarTitle), 'kampinfo');
		if ($this->canDo->get($this->entityName.'.create')) {
			JToolBarHelper :: addNew($this->entityName . '.add');
		}
		if ($this->isUserAuthorisedFor($this->entityName.'.edit')) {
			JToolBarHelper :: editList($this->entityName . '.edit');
		}
		if ($this->isUserAuthorisedFor($this->entityName.'.delete')) {
			JToolBarHelper :: deleteList('', $this->entityName . 's.delete');
		}
		if ($this->entityName == 'hitcamp' || $this->entityName == 'hitsite') {
			if ($this->canDo->get($this->entityName.'.edit.state')) {
				JToolBarHelper :: divider();
				JToolBarHelper :: publish($this->entityName . 's.publish', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper :: unpublish($this->entityName . 's.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			}
		}
		JToolBarHelper :: divider();
		if (JFactory::getUser()->authorise('core.admin', 'com_kampinfo')) {
			JToolBarHelper :: preferences('com_kampinfo');
		}
	}
	
	private function isUserAuthorisedFor($func) {
		if ($this->canDo->get($func)) {
			return true;
		}
		foreach ($this->items as $item) {
			$key = $func .'.'. (int)$item->id;
			if ($this->canDo->get($key)) {
				return true;
			}
		}
		return false;
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
		$ids = array();
		$ids[] = $item->id;
		$this->canDo = KampInfoHelper::getActions($this->entityName, $ids);

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