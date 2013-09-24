<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class voor Statistieken.
 */
class KampInfoViewStatistiek extends JView {
	
	function display($tpl = null) {
		// Assign data to the view
		$this->statistiek = $this->get('Statistiek');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError :: raiseError(500, implode('<br />', $errors));
			return false;
		}

		JHTML::stylesheet("statistiek.css", "media/com_kampinfo/css/");

		$document =& JFactory :: getDocument();
		$document->addScript("https://www.google.com/jsapi");
		$document->addScriptDeclaration("google.load('visualization', '1', {packages: ['". $this->statistiek->getPackages() ."']});");
		$document->addScriptDeclaration($this->statistiek->getDrawVisualization() . "\n\ngoogle.setOnLoadCallback(drawVisualization);");

		// Display the view
		parent :: display($tpl);
	}
}