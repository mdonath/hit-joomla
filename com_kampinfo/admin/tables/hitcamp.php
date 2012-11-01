<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * HIT Camp Table class
 */
class KampInfoTableHitCamp extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) 
	{
		parent::__construct('#__kampinfo_hitcamp', 'id', $db);
	}
        
  public function store($updateNulls = false)
    { 
		if (is_array($this->icoontjes)) {
		    $this->icoontjes = implode(',', $this->icoontjes);
		    $this->activiteitengebieden = implode(',', $this->activiteitengebieden);
		}
		else {
    		$this->icoontjes = implode(',', array());
    		$this->activiteitengebieden = implode(',', array());
    	}
   
		// Attempt to store the data.
		return parent::store($updateNulls);
	}
}