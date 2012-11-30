<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class voor een HIT Kamponderdeel.
 */
class KampInfoViewActiviteit extends JView {
	
	function display($tpl = null) {
		// Assign data to the view
		$this->activiteit = $this->get('Activiteit');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError :: raiseError(500, implode('<br />', $errors));
			return false;
		}

		JHTML::stylesheet("activiteit.css", "media/com_kampinfo/css/");

		$document =& JFactory::getDocument();
		$document->setTitle($this->activiteit->naam . ' in ' . $this->activiteit->plaats);

		// Display the view
		parent :: display($tpl);
	}
}