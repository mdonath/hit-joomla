<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class voor een HIT Kamponderdeel.
 */
class KampInfoViewActiviteit extends JViewLegacy {
	
	function display($tpl = null) {
		// Assign data to the view
		$this->activiteit = $this->get('Activiteit');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		$document = JFactory::getDocument();
		$document->addStyleSheet("media/com_kampinfo/css/activiteit.css");
		$document->setTitle($this->activiteit->naam . ' in ' . $this->activiteit->plaats);

		// Display the view
		parent::display($tpl);
	}
}