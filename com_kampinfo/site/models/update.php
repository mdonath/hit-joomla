<?php defined('_JEXEC') or die('Restricted access');

include_once JPATH_COMPONENT_ADMINISTRATOR.'/models/import.php';

/**
 * KampInfo Update Model.
 */
class KampInfoModelUpdate extends KampInfoModelImport {
	
	public function download() {
		$params = &JComponentHelper::getParams('com_kampinfo');
		
		// Voor welk jaar staat in de configuratie
		$configuredSecret = $params->get('downloadSecret');
		
		$jinput = JFactory::getApplication()->input;
		$givenSecret = $jinput->get('secret', '', 'string');
		
		if ($configuredSecret == $givenSecret) {
			$ok = $this->downloadInschrijvingen();
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
