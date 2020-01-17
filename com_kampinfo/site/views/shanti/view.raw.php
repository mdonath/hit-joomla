<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * RAW View class voor Shanti.
 */
class KampInfoViewShanti extends JViewLegacy {
	
	function display($tpl = null) {
		$document = JFactory::getDocument();
		$document->setMimeEncoding('text/plain');

		$data = $this->get('ShantiData');
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		echo ($this->shanti($data));
	}
	
	/**
	 * @param unknown $hit
	 */
	private function shanti($rows) {
		$output = "[\n";
		$sep = '';
		foreach ($rows as $row) {
			$output .= $sep;
			$output .= $this->printRow($row);
			$sep = ",\n";
		}
		$output .= "]";
		return $output;
	}
	
	private function printRow($row) {
		$output = '';
		$output .= "{";
		$sep = " ";
		foreach ($row as $field => $value) {
			$output .= $sep . $this->quote($field) . ': ';
			if ($this->endswith($field, '_month')) {
				$output .= $this->quote($this->maandnaam($value));
			} else {
				if (!is_numeric($value)) {
					$output .= $this->quote($value);
				} else {
					// remove illegal (for json) leading zeroes
					$output .= (string)((int)($value));
				}
			}
			$sep = "\n, ";
		}
		$output .= "\n}\n";
		return $output;
	}
	
	//////////////////////////////////////
	
	private function quote($text) {
		return "\"".str_replace('"', '\"', $text)."\"";
	}

	private function endswith($string, $test) {
		$strlen = strlen($string);
		$testlen = strlen($test);
		if ($testlen > $strlen) return false;
		return substr_compare($string, $test, -$testlen) === 0;
	}
	
	private function maandnaam($nummer) {
		$maanden = array(
				'januari',
				'februari',
				'maart',
				'april',
				'mei',
				'juni',
				'juli',
				'augustus',
				'september',
				'oktober',
				'november',
				'december'
		);
		return $maanden[$nummer-1];
	}
	
}