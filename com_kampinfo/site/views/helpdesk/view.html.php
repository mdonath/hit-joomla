<?php defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class voor Helpdesk
 */
class KampInfoViewHelpdesk extends JViewLegacy {
	
	function display($tpl = null) {
		// Assign data to the view
		$this->jaar = $this->get('Jaar');
		$this->project = $this->get('Project');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::base() . 'media/com_kampinfo/css/helpdesk.css', 'text/css', 'screen');
		
		// Display the view
		parent::display($tpl);
	}
}