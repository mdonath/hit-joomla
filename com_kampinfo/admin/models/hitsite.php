<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * HitSite Model.
*/
class KampInfoModelHitSite extends JModelAdmin {
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param       type    The table type to instantiate
	 * @param       string  A prefix for the table class name. Optional.
	 * @param       array   Configuration array for model. Optional.
	 * @return      JTable  A database object
	 * @since       2.5
	 */
	public function getTable($type = 'HitSite', $prefix = 'KampInfoTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Method to get the record form.
	 *
	 * @param       array   $data           Data for the form.
	 * @param       boolean $loadData       True if the form is to load its own data (default case), false if not.
	 * @return      mixed   A JForm object on success, false on failure
	 * @since       2.5
	 */
	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		$form = $this->loadForm('com_kampinfo.hitsite', 'hitsite',
				array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return      mixed   The data for the form.
	 * @since       2.5
	 */
	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_kampinfo.edit.hitsite.data', array());
		if (empty($data)) {
			$data = $this->getItem();
		}
		return $data;
	}

	/**
	 * Method to check if it's OK to delete a hitcamp. Overwrites JModelAdmin::canDelete
	 */
	protected function canDelete($record) {
		if (!empty($record->id)) {
			$user = JFactory::getUser();
			return $user->authorise("hitsite.delete", "com_kampinfo.hitsite." . $record->id );
		}
	}

	public function	batch($commands, $pks, $contexts) {
		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);
		
		// Remove any values of zero.
		if (array_search(0, $pks, true))
		{
			unset($pks[array_search(0, $pks, true)]);
		}
		
		if (empty($pks))
		{
			$this->setError(JText::_('Niets geselecteerd!'));
			return false;
		}
		
		if ($commands['group_action'] == 'akkoordPlaats') {
			$this->akkoordPlaats($pks, 1);
		} else if ($commands['group_action'] == 'nietAkkoordPlaats') {
			$this->akkoordPlaats($pks, 0);
		}
		
		
		return true;
	} 
	
	public function akkoordPlaats($ids, $value) {
		$cids = implode( ',', $ids);
	
		$db =& JFactory::getDBO();
		$query = 'UPDATE #__kampinfo_hitsite SET akkoordHitPlaats = '.(int) $value . ' WHERE id IN ( '.$cids.' )';
		$db->setQuery($query);
		$result = $db->query();
	}
}