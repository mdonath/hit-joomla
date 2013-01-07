<?php defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * Downloads Controller.
 */
class KampInfoControllerDownloads extends JControllerAdmin
{
        /**
         * Proxy for getModel.
         * @since       2.5
         */
        public function getModel($name = 'Downloads', $prefix = 'KampInfoModel', $config = array('ignore_request' => true)) 
        {
                $model = parent::getModel($name, $prefix, $config);
                return $model;
        }
}