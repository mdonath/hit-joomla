<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die('Restricted access');

if (!Factory::getUser()->authorise('core.manage', 'com_kampinfo')) {
	return JError::raiseWarning(404, Text::_('JERROR_ALERTNOAUTHOR'));
}

// require helper file
JLoader::register('KampInfoHelper', dirname(__FILE__) .'/helpers/kampinfo.php');

// Set some global property
$document = Factory::getDocument();
$document->addStyleDeclaration('.icon-48-kampinfo {background-image: url(../media/com_kampinfo/images/kampinfo-48x48.png);}');

$controller	= JControllerLegacy::getInstance('KampInfo');
$controller->execute(Factory::getApplication()->input->getCmd('task'));
$controller->redirect();
