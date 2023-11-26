<?php

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;

$displayData = [
    'textPrefix' => 'COM_KAMPINFO_HITPROJECT',
    'formURL'    => 'index.php?option=com_kampinfo',
];

$user = Factory::getApplication()->getIdentity();

if ($user->authorise('core.create', 'com_kampinfo') || count($user->getAuthorisedCategories('com_kampinfo', 'core.create')) > 0) {
    $displayData['createURL'] = 'index.php?option=com_kampinfo&task=project.add';
}

echo LayoutHelper::render('joomla.content.emptystate', $displayData);
