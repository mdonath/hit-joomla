<?php

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die('Restricted Access');

$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
?>

<thead>
    <tr>
        <?php if ($this->canDo->get('hitproject.edit') || $this->canDo->get('hitproject.delete')) { ?>
            <td class="w-1 text-center">
                <?php echo HTMLHelper::_('grid.checkall'); ?>
            </td>
        <?php } ?>
        <th scope="col">
            <?php echo HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_HITPROJECTS_HEADING_YEAR', 'p.jaar', $listDirn, $listOrder); ?>
        </th>
        <th scope="col">
            <?php echo Text::_('COM_KAMPINFO_HITPROJECTS_HEADING_INSCHRIJVING_START'); ?>
        </th>
        <th scope="col">
            <?php echo Text::_('COM_KAMPINFO_HITPROJECTS_HEADING_INSCHRIJVING_EIND'); ?>
        </th>
        <th scope="col" class="hidden-phone">
            <?php echo Text::_('COM_KAMPINFO_HITPROJECTS_HEADING_INSCHRIJVING_WIJZIGEN_TOT'); ?>
        </th>
        <th scope="col" class="hidden-phone">
            <?php echo Text::_('COM_KAMPINFO_HITPROJECTS_HEADING_INSCHRIJVING_KOSTELOOS_ANNULEREN'); ?>
        </th>
        <th scope="col" class="hidden-phone">
            <?php echo Text::_('COM_KAMPINFO_HITPROJECTS_HEADING_INSCHRIJVING_GEEN_RESTITIE'); ?>
        </th>
        <th scope="col" class="hidden-phone">
            <?php echo Text::_('COM_KAMPINFO_HITPROJECTS_HEADING_INNINGSDATUM'); ?>
        </th>
        <th width="5" class="hidden-phone">
            <?php echo HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_HITPROJECTS_HEADING_ID', 'p.id', $listDirn, $listOrder); ?>
        </th>
    </tr>
</thead>
