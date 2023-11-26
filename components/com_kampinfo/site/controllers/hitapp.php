<?php

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');


class KampInfoControllerHitApp extends JControllerForm {
	
	public function getModel($name = '', $prefix = 'KampInfoModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
	
	public function generate()
	{
		$app    = Factory::getApplication();
		$model  = $this->getModel('hitapp');
		$model->generate();
		Factory::getApplication()->close();
	}
		
}