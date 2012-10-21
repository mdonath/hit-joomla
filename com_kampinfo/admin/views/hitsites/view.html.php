<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HitSites View
 */
class KampInfoViewHitSites extends JViewLegacy {

	protected $items;
	protected $pagination;
	protected $state;
	
	function display($tpl = null) {

		// Initialise variables.
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper :: title(JText :: _('COM_KAMPINFO_HITSITES_MANAGER'), 'kampinfo');
		JToolBarHelper :: addNewX('hitsite.add');
		JToolBarHelper :: editListX('hitsite.edit');
		JToolBarHelper :: divider();
		JToolBarHelper :: deleteListX('', 'hitsites.delete');
	}

}