<?php

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Layout\LayoutHelper;

$displayData = [
    'textPrefix' => 'COM_KAMPINFO_DOWNLOADS',
    'formURL'    => 'index.php?option=com_kampinfo',
];

$user = $this->getCurrentUser();

echo LayoutHelper::render('joomla.content.emptystate', $displayData);
