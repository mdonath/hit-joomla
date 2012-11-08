<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// require helper file
JLoader::register('KampInfoHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'kampinfo.php');
 
// Set some global property
$document = JFactory::getDocument();
$document->addStyleDeclaration('.icon-48-kampinfo {background-image: url(../media/com_kampinfo/images/kampinfo-48x48.png);}');
 
$controller	= JControllerLegacy::getInstance('KampInfo');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
