<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');


class KampInfoControllerHitApp extends JControllerForm {
	
	public function getModel($name = '', $prefix = 'KampInfoModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
	
	public function generate()
	{
		$app    = JFactory::getApplication();
		$model  = $this->getModel('hitapp');
		$model->generate();
		JFactory::getApplication()->close();
	}
		
}