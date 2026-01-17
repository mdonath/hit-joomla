<?php

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
?>

<thead>
    <tr>
        <td class="w-1 text-center">
            <?= HTMLHelper::_('grid.checkall'); ?>
        </td>
        <th scope="col">
            <?= HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_HITPROJECTS_HEADING_YEAR', 'p.jaar', $listDirn, $listOrder); ?>
        </th>
        <th scope="col">
            <?= Text::_('COM_KAMPINFO_HITPROJECTS_HEADING_INSCHRIJVING_START'); ?>
        </th>
        <th scope="col">
            <?= Text::_('COM_KAMPINFO_HITPROJECTS_HEADING_LOTERIJ_START'); ?>
        </th>
        <th scope="col">
            <?= Text::_('COM_KAMPINFO_HITPROJECTS_HEADING_LOTERIJ_EIND'); ?>
        </th>
        <th scope="col">
            <?= Text::_('COM_KAMPINFO_HITPROJECTS_HEADING_INSCHRIJVING_EIND'); ?>
        </th>
        <th width="5" class="hidden-phone">
            <?= HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_HITPROJECTS_HEADING_ID', 'p.id', $listDirn, $listOrder); ?>
        </th>
    </tr>
</thead>
