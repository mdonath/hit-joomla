<?php defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * HIT Camp Table class.
 */
class KampInfoTableHitCamp extends JTable {

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) {
		parent::__construct('#__kampinfo_hitcamp', 'id', $db);
	}

	/**
	 * Method to compute the default name of the asset. The default name is in the form `table_name.id` where id is the value of the primary key of the table.
	 *  @return      string
	 *  @since       2.5
	 */
	protected function _getAssetName() {
		$k = $this->_tbl_key;
		$id = (int) $this->$k;
		return 'com_kampinfo.hitcamp.'.$id;
	}

	/**
	 * Method to return the title to use for the asset table.
	 * @return      string
	 * @since       2.5
	 */
	protected function _getAssetTitle() {
		return $this->naam;
	}


	/**
	 * Method to get the asset-parent-id of the item
	 * @return      int
	 */
	protected function _getAssetParentId() {
		// We will retrieve the parent-asset from the Asset-table
		$assetParent = JTable::getInstance('Asset');
		// Default: if no asset-parent can be found we take the global asset
		$assetParentId = $assetParent->getRootId();

		// Find the parent-asset
		$assetParent->loadByName('com_kampinfo.hitsite.' . $this->hitsite_id);

		if ($assetParent->id) {
			$assetParentId = $assetParent->id;
		} else {
			$assetParent->loadByName('com_kampinfo');
			if ($assetParent->id) {
				$assetParentId = $assetParent->id;
			}
		}
		return $assetParentId;
	}


	public function load($keys = NULL, $reset = true) {
		$result = parent::load($keys, $reset);
		$this->icoontjes = explode(',', $this->icoontjes);
		$this->activiteitengebieden = explode(',', $this->activiteitengebieden);
		$this->doelgroepen = explode(',', $this->doelgroepen);
		return $result;
	}
	
	public function store($updateNulls = false) {
		if (is_array($this->icoontjes)) {
			$this->icoontjes = implode(',', $this->icoontjes);
		} else {
			$this->icoontjes = '';
		}
		
		if (is_array($this->activiteitengebieden)) {
			$this->activiteitengebieden = implode(',', $this->activiteitengebieden);
		} else {
			$this->activiteitengebieden = '';
		}

		if (is_array($this->doelgroepen)) {
			$this->doelgroepen = implode(',', $this->doelgroepen);
		} else {
			$this->doelgroepen = '';
		}
			
		// Attempt to store the data.
		return parent::store($updateNulls);
	}
	
	/**
	 * Overridden bind function, wordt aangeroepen na de #load() en voorafgaand aan de #store
	 *
	 * @param       array           named array
	 * @return      null|string     null if operation was satisfactory, otherwise returns an error
	 * @see JTable:bind
	 * @since 1.5
	 */
	public function bind($array, $ignore = '') {
		// Bind the rules.
		if (isset($array['rules']) && is_array($array['rules'])) {
			$this->setRules(new JAccessRules($array['rules']));
		}
		
		$result = parent::bind($array, $ignore);
		if (empty($array['icoontjes'])) {
			$this->icoontjes = '';
		}
		if (empty($array['activiteitengebieden'])) {
			$this->activiteitengebieden = '';
		}
		if (empty($array['doelgroepen'])) {
			$this->doelgroepen = '';
		}
		return $result;
	}
}