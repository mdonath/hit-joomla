<?php


// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * Hit Camp Model
 */
class KampInfoModelHitCamp extends JModelAdmin {

	public function getTable($type = 'HitCamp', $prefix = 'KampInfoTable', $config = array ()) {
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array (), $loadData = true) {
		// Get the form.
		$form = $this->loadForm('com_kampinfo.hitcamp', 'hitcamp', array (
			'control' => 'jform',
			'load_data' => $loadData
		));
		if (empty ($form)) {
			return false;
		}
		return $form;
	}

	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_kampinfo.edit.hitcamp.data', array ());
		if (empty ($data)) {
			$data = $this->getItem();
		}
		return $data;
	}

	
	/**
	 * Method to check if it's OK to delete a hitcamp. Overwrites JModelAdmin::canDelete
	 */
	protected function canDelete($record) {
		if(!empty($record->id)){
			$user = JFactory::getUser();
			return $user->authorise("hitcamp.delete", "com_kampinfo.hitcamp." . $record->id);
		}
	}

	public function	batch($commands, $pks, $contexts) {
		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);
	
		// Remove any values of zero.
		if (array_search(0, $pks, true)) {
			unset($pks[array_search(0, $pks, true)]);
		}
	
		if (empty($pks)) {
			$this->setError(JText::_('Niets geselecteerd!'));
			return false;
		}
	
		if ($commands['group_action'] == 'akkoordPlaats') {
			$this->akkoordPlaats($pks, 1);
		} else if ($commands['group_action'] == 'nietAkkoordPlaats') {
			$this->akkoordPlaats($pks, 0);
		} else if ($commands['group_action'] == 'akkoordKamp') {
			$this->akkoordKamp($pks, 1);
		} else if ($commands['group_action'] == 'nietAkkoordKamp') {
			$this->akkoordKamp($pks, 0);
		} else {
			$this->setError(JText::_('Ik weet niet wat ik moet doen?! : '. $commands['group_action']));
			return false;
		}
		return true;
	}
	
	public function akkoordPlaats($ids, $value) {
		$cids = implode( ',', $ids);
	
		$db = JFactory::getDBO();
		$query = 'UPDATE #__kampinfo_hitcamp SET akkoordHitPlaats = '.(int) $value . ' WHERE id IN ( '.$cids.' )';
		$db->setQuery($query);
		$result = $db->query();
	}
	
	public function akkoordKamp($ids, $value) {
		$cids = implode( ',', $ids);
	
		$db = JFactory::getDBO();
		$query = 'UPDATE #__kampinfo_hitcamp SET akkoordHitKamp= '.(int) $value . ' WHERE id IN ( '.$cids.' )';
		$db->setQuery($query);
		$result = $db->query();
	}
}