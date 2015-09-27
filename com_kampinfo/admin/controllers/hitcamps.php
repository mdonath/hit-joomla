<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * HitCamps Controller.
*/
class KampInfoControllerHitCamps extends JControllerAdmin {
	
	public function __construct($config = array()){
		parent::__construct($config);
		$this->registerTask('nietAkkoordPlaats', 'akkoordPlaats');
		$this->registerTask('nietAkkoordKamp', 'akkoordKamp');
	}
	
	/**
	 * Proxy for getModel.
	 * @since       2.5
	 */
	public function getModel($name = 'HitCamp', $prefix = 'KampInfoModel', $config = array('ignore_request' => true)) {
		return parent::getModel($name, $prefix, $config);
	}
	
	public function akkoordPlaats() {
		$ids = JRequest::getVar('cid', array(), '', 'array');
		JArrayHelper::toInteger($ids );
		$cids = implode( ',', $ids);
		$values = array('akkoordPlaats' => 1, 'nietAkkoordPlaats' => 0);
		$task = $this->getTask();
		$value = JArrayHelper::getValue($values, $task, 0, 'int');
	
		$db = JFactory::getDBO();
		$query = 'UPDATE #__kampinfo_hitcamp' . ' SET akkoordHitPlaats = '.(int) $value . ' WHERE id IN ( '.$cids.' )';
		$db->setQuery($query);
		$result = $db->query();
	
		$redirectTo = JRoute::_('index.php?option='.JRequest::getVar('option').'&view=hitcamps', false);
		$this->setRedirect($redirectTo);
	}
	
	public function akkoordKamp() {
		$ids = JRequest::getVar('cid', array(), '', 'array');
		JArrayHelper::toInteger($ids );
		$cids = implode( ',', $ids);
		$values = array('akkoordKamp' => 1, 'nietAkkoordKamp' => 0);
		$task = $this->getTask();
		$value = JArrayHelper::getValue($values, $task, 0, 'int');
	
		$db = JFactory::getDBO();
		$query = 'UPDATE #__kampinfo_hitcamp' . ' SET akkoordHitKamp = '.(int) $value . ' WHERE id IN ( '.$cids.' )';
		$db->setQuery($query);
		$result = $db->query();
	
		$redirectTo = JRoute::_('index.php?option='.JRequest::getVar('option').'&view=hitcamps', false);
		$this->setRedirect($redirectTo);
	}
}