<?php
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class voor de HIT Kiezer.
 */
class KampInfoViewHitkiezer extends JView {

	// Overwriting JView display method
	function display($tpl = null) {
		// Assign data to the view
		$this->jaar = $this->get('Jaar');
		$this->project = $this->get('Project');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError :: raiseError(500, implode('<br />', $errors));
			return false;
		}

		$document =& JFactory :: getDocument();
		JHTML::stylesheet("hitkiezer.css", "media/com_kampinfo/css/");

		$document->addScript("https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js");
		$document->addScript("media/com_kampinfo/js/jquery.cookies.2.2.0.min.js");

		$json = json_encode($this->project);
		$document->addScriptDeclaration('var hit = '.$json);		

		$document->addScript("media/com_kampinfo/js/common.js");
		$document->addScript("media/com_kampinfo/js/hitkiezer.js");
		
		// Display the view
		parent :: display($tpl);
	}
}