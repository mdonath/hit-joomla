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
		return JTable :: getInstance($type, $prefix, $config);
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
		$data = JFactory :: getApplication()->getUserState('com_kampinfo.edit.hitcamp.data', array ());
		if (empty ($data)) {
			$data = $this->getItem();
		}
		return $data;
	}
	
	public function getItem($pk = null) {
		if ($item = parent::getItem($pk)) {
			$item->icoontjes = explode(',', $item->icoontjes);
			$item->activiteitengebieden = explode(',', $item->activiteitengebieden);
			$item->doelgroepen = explode(',', $item->doelgroepen);
		}
		   
		return $item;
	}
	
	/**
	 * Method to check if it's OK to delete a hitcamp. Overwrites JModelAdmin::canDelete
	 */
	protected function canDelete($record) {
		if( !empty( $record->id ) ){
			$user = JFactory::getUser();
			return $user->authorise("hitcamp.delete", "com_kampinfo.hitcamp." . $record->id );
		}
	}
}