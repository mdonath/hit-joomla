<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * HIT Site Table class
*/
class KampInfoTableHitSite extends JTable {

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) {
		parent::__construct('#__kampinfo_hitsite', 'id', $db);
	}
	
	/**
	 * Overridden bind function
	 *
	 * @param       array           named array
	 * @return      null|string     null if operation was satisfactory, otherwise returns an error
	 * @see JTable:bind
	 * @since 1.5
	 */
	public function bind($array, $ignore = '') {
		// Bind the rules.
		if (isset($array['rules']) && is_array($array['rules'])) {
			$rules = new JAccessRules($array['rules']);
			$this->setRules($rules);
		}
		return parent::bind($array, $ignore);
	}

	/**
	 * Method to compute the default name of the asset. The default name is in the form `table_name.id` where id is the value of the primary key of the table.
	 *  @return      string
	 *  @since       2.5
	 */
	protected function _getAssetName() {
		$k = $this->_tbl_key;
		$id = (int) $this->$k;
		return 'com_kampinfo.hitsite.'.$id;
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
	
		// The item has the component as asset-parent
		$assetParent->loadByName('com_kampinfo');

		// Return the found asset-parent-id
		if ($assetParent->id) {
			$assetParentId = $assetParent->id;
		}
		return $assetParentId;
	}
}