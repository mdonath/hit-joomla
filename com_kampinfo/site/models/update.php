<?php defined('_JEXEC') or die('Restricted access');

include_once JPATH_COMPONENT_ADMINISTRATOR.'/models/import.php';

/**
 * KampInfo Update Model.
 */
class KampInfoModelUpdate extends KampInfoModelImport {
	
	public function download() {
		$params =JComponentHelper::getParams('com_kampinfo');
		
		// Voor welk jaar staat in de configuratie
		$configuredSecret = $params->get('downloadSecret');
		
		$jinput = JFactory::getApplication()->input;
		$givenSecret = $jinput->get('secret', '', 'string');
		
		if ($configuredSecret == $givenSecret) {
			$wat = $jinput->get('wat', '', 'string');
			$ok = false;
			if ($wat == '') {
				$ok = $this->downloadInschrijvingen();
			} else if ($wat == 'deelnemers') {
				$ok = $this->downloadDeelnemergegevens();
			}
			if ($ok) {
				echo "OK\n";
			} else {
				echo "NOK\n";
			}
		} else {
			echo "What's the magic word?";
		}
	}
	
}
