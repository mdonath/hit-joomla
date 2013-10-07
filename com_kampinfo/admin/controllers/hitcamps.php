<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * HitCamps Controller.
*/
class KampInfoControllerHitCamps extends JControllerAdmin {
	/**
	 * Proxy for getModel.
	 * @since       2.5
	 */
	public function getModel($name = 'HitCamp', $prefix = 'KampInfoModel', $config = array('ignore_request' => true)) {
		return parent::getModel($name, $prefix, $config);
	}
}