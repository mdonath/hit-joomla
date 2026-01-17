<?php

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\HTML\HTMLHelper;

$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
?>

<thead>
    <tr>
        <th scope="col">
            <?= HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_DOWNLOADS_HEADING_YEAR', 'd.jaar', $listDirn, $listOrder); ?>
        </th>
        <th scope="col">
            <?= HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_DOWNLOADS_HEADING_SOORT', 'd.soort', $listDirn, $listOrder); ?>
        </th>
        <th scope="col">
            <?= HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_DOWNLOADS_HEADING_BIJGEWERKTOP', 'd.bijgewerktOp', $listDirn, $listOrder); ?>
        </th>
        <th scope="col">
            <?= HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_DOWNLOADS_HEADING_MELDING', 'd.melding', $listDirn, $listOrder); ?>
        </th>
        <th scope="col">
            <?= HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_DOWNLOADS_HEADING_ID', 'd.id', $listDirn, $listOrder); ?>
        </th>
    </tr>
</thead>
