<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');


class KampInfoControllerUpdate extends JControllerForm {
	
	public function getModel($name = '', $prefix = 'KampInfoModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
	
	public function download()
	{
		$app    = JFactory::getApplication();
		$model  = $this->getModel('update');
		$model->download();
		JFactory::getApplication()->close();
	}
		
}