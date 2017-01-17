<?php defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * HitProject Model.
*/
class KampInfoModelHitProject extends JModelAdmin {
	
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param       type    The table type to instantiate
	 * @param       string  A prefix for the table class name. Optional.
	 * @param       array   Configuration array for model. Optional.
	 * @return      JTable  A database object
	 * @since       2.5
	 */
	public function getTable($type = 'HitProject', $prefix = 'KampInfoTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getKampen() {
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$item = $this->getItem();
		$jaarId = $item->id;
		return $this->getVolledigePlaatsenEnKampenVanJaar($db, $jaarId);
	}
	
	private function getVolledigePlaatsenEnKampenVanJaar($db, $jaarId) {
		$query = $db->getQuery(true)
		-> select('c.*, s.naam as plaats, p.jaar')
		-> from($db->quoteName('#__kampinfo_hitcamp', 'c'))
		-> join('LEFT', '#__kampinfo_hitsite AS s ON (c.hitsite_id = s.id)')
		-> join('LEFT', '#__kampinfo_hitproject AS p ON (s.hitproject_id = p.id)')
		-> where($db->quoteName('p.id') .'='. (int) $db->escape($jaarId))
		-> order('c.naam');
		$db->setQuery($query);
	
		$result = $db->loadObjectList();
	
		// expand icons
		foreach ($result as $kamp) {
			$query = $db->getQuery(true);
			$query->select('i.bestandsnaam as naam, i.tekst, i.volgorde');
			$query->from('#__kampinfo_hiticon i');
				
			$values=array();
			foreach(explode(',', $kamp->icoontjes) as $naam) {
				$values[] = $db->quote($db->escape($naam));
			}
			$query->where('i.bestandsnaam in (' . implode(',', $values) . ')');
			$query->order('i.volgorde');
				
			$db->setQuery($query);
			$icons = $db->loadObjectList();
			$kamp->icoontjes = $icons;
		}
	
		return $result;
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
		$form = $this->loadForm('com_kampinfo.hitproject', 'hitproject', array('control' => 'jform', 'load_data' => $loadData));
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
		$data = JFactory::getApplication()->getUserState('com_kampinfo.edit.hitproject.data', array());
		if (empty($data)) {
			$data = $this->getItem();
		}
		return $data;
	}

}