<?php

use Joomla\CMS\Factory;

defined('_JEXEC') or die('Restricted access');
 
// import joomla controller library
jimport('joomla.application.component.controller');
 
// Get an instance of the controller prefixed by KampInfo
$controller = JControllerLegacy::getInstance('KampInfo');

// require helper file
JLoader::register('KampInfoHelper', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/kampinfo.php');

// Perform the Request task
$controller->execute(Factory::getApplication()->input->getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();
