<?php

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
?>

<thead>
    <tr>
        <?php if ($this->canDo->get('hiticon.edit') || $this->canDo->get('hiticon.delete')) { ?>
            <td class="w-1 text-center">
                <?php echo HTMLHelper::_('grid.checkall'); ?>
            </td>
        <?php } ?>
        <th scope="col">
            <?php echo HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_HITICONS_HEADING_VOLGORDE', 'volgorde', $listDirn, $listOrder); ?>
        </th>
        <th scope="col">
            Icoon
        </th>
        <th scope="col">
            <?php echo Text::_('COM_KAMPINFO_HITICONS_HEADING_BESTANDSNAAM'); ?>
        </th>
        <th scope="col" class="hidden-phone">
            <?php echo Text::_('COM_KAMPINFO_HITICONS_HEADING_TEKST'); ?>
        </th>
        <th scope="col" class="hidden-phone">
            <?php echo Text::_('COM_KAMPINFO_HITICONS_HEADING_UITLEG'); ?>
        </th>
        <th scope="col">
            <?php echo HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_HITICONS_HEADING_SOORT', 'soort', $listDirn, $listOrder); ?>
        </th>
        <th scope="col" class="w-1 hidden-phone">
            <?php echo HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_HITICONS_HEADING_ID', 'id', $listDirn, $listOrder); ?>
        </th>
    </tr>
</thead>
