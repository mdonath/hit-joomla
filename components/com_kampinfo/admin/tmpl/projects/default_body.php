<?php

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$user = $this->getCurrentUser();

$canEdit    = $user->authorise('hitproject.edit', 'com_kampinfo');
$canDelete  = $user->authorise('hitproject.delete', 'com_kampinfo');
?>

<tbody>
    <?php foreach ($this->items as $i => $item) : ?>
        <tr>
            <td>
                <?= HTMLHelper::_('grid.id', $i, $item->id); ?>
            </td>
            <td>
                <?php if ($canEdit) : ?>
                    <a href="<?= Route::_('index.php?option=com_kampinfo&task=project.edit&id=' . (int)$item->id); ?>">
                        <?= $item->jaar ?>
                    </a>
                    <?php else : ?>
                        <?= $item->jaar ?>
                    <?php endif; ?>
            </td>
            <td>
                <?=  HTMLHelper::date($item->inschrijvingStartdatum, Text::_('COM_KAMPINFO_DATETIME_FORMAT')); ?>
            </td>
            <td>
                <?= $item->loterijStartdatum ? HTMLHelper::date($item->loterijStartdatum, Text::_('COM_KAMPINFO_DATETIME_FORMAT')) : '-'; ?>
            </td>
            <td>
                <?= $item->loterijEinddatum ? HTMLHelper::date($item->loterijEinddatum, Text::_('COM_KAMPINFO_DATETIME_FORMAT')) : '-'; ?>
            </td>
            <td>
                <?= HTMLHelper::date($item->inschrijvingEinddatum, Text::_('COM_KAMPINFO_DATETIME_FORMAT')); ?>
            </td>
            <td class="hidden-phone">
                <?= $item->id; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
