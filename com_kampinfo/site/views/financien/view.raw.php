<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * RAW View class voor Financien.
 */
class KampInfoViewFinancien extends JViewLegacy {
	
	function display($tpl = null) {
		$document = JFactory::getDocument();
		$document->setMimeEncoding('text/plain');

		$data = $this->get('FinancienData');
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		echo ($this->header($data));
		echo ($this->generate($data));
	}
	
	private function header($rows) {
		$output = "";
		$sep = "";
		foreach ($rows[0] as $field => $value) {
			$output .= $sep;
			$output .= $this->quote($field);
			$sep = ";";
		}
		$output .= "\n";
		return $output;
	}

	private function generate($rows) {
		$output = "";
		foreach ($rows as $row) {
			$output .= $this->printRow($row);
		}
		$output .= "";
		return $output;
	}
	
	private function printRow($row) {
		$output = '';
		$sep = "";
		foreach ($row as $field => $value) {
			$output .= $sep;
			$output .= $this->quote($value);
			$sep = ";";
		}
		$output .= "\n";
		return $output;
	}
	
	//////////////////////////////////////
	
	private function quote($text) {
		return "\"".str_replace('"', '\'', $text)."\"";
// 		return "\"".$text."\"";
		// 		return $text;
	}	
}