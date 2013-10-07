<?php defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class voor het Overzicht
 */
class KampInfoViewOverzicht extends JView {
	
	// Overwriting JView display method
	function display($tpl = null) {
		// Assign data to the view
		$this->project = $this->get('Project');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError :: raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::base() . 'media/com_kampinfo/css/overzicht.css', 'text/css', 'screen');
		
		// Display the view
		parent :: display($tpl);
	}
}