<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * HitSites Controller.
 */
class KampInfoControllerHitSites extends JControllerAdmin {
	
	public function __construct($config = array()){
		parent::__construct($config);
		$this->registerTask('nietAkkoordPlaats', 'akkoordPlaats');
	}
	
	/**
	* Proxy for getModel.
	* @since       2.5
	*/
	public function getModel($name = 'HitSite', $prefix = 'KampInfoModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	public function akkoordPlaats() {
		$ids = JRequest::getVar('cid', array(), '', 'array');

		JArrayHelper::toInteger($ids );
		$cids = implode( ',', $ids);
		$values = array('akkoordPlaats' => 1, 'nietAkkoordPlaats' => 0);
		$task = $this->getTask();
		$value = JArrayHelper::getValue($values, $task, 0, 'int');
		
		$db =& JFactory::getDBO();
		$query = 'UPDATE #__kampinfo_hitsite SET akkoordHitPlaats = '.(int) $value . ' WHERE id IN ( '.$cids.' )';
		$db->setQuery($query);
		$result = $db->query();

		$redirectTo = JRoute::_('index.php?option='.JRequest::getVar('option').'&view=hitsites', false);
		$this->setRedirect($redirectTo);
	}
	
}