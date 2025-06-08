<?php

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$user = $this->getCurrentUser();
?>

<tbody>
    <?php foreach ($this->items as $i => $item) : ?>
        <?php
            $canEdit        = $user->authorise('hitcamp.edit', 'com_kampinfo.camp.' . (int)$item->id);
            $canEditPlaats  = $user->authorise('hitsite.edit', 'com_kampinfo.site.' . (int)$item->hitsite_id);
            $canPublish     = $user->authorise('hitcamp.edit.state', 'com_kampinfo.camp.' . (int)$item->id);
        ?>
        <tr>
            <td>
                <?= HTMLHelper::_('grid.id', $i, $item->id) ?>
            </td>
            <td>
                <?= HTMLHelper::_('jgrid.published', $item->published, $i, 'camps.', $canPublish, 'cb') ?>
            </td>
            <td>
                <?= HTMLHelper::_('kamp.naam', $item, $canEdit) ?>
            </td>
            <td>
                <?= HTMLHelper::_('akkoord.akkoordkamp', $item->akkoordHitKamp, $i, 'camps.', $canEdit) ?>
            </td>
            <td>
                <?= $item->plaats ?>
            </td>
            <td>
                <?= HTMLHelper::_('akkoord.akkoordplaats', $item->akkoordHitPlaats, $i, 'camps.', $canEditPlaats) ?>
            </td>
            <td>
                <?= $item->jaar ?>
            </td>
            <td>
                <?= Text::_($item->minimumAantalDeelnemers) ?>
            </td>
            <td>
                <?= Text::_($item->gereserveerd) ?>
            </td>
            <td>
                <?= Text::_($item->aantalDeelnemers) ?>
            </td>
            <td>
                <?= Text::_($item->maximumAantalDeelnemers) ?> (<?= Text::_($item->maximumAantalDeelnemersOrigineel) ?>)
            </td>
            <td>
                € <?= Text::_($item->deelnamekosten) ?>
            </td>
            <td>
                <?= $item->id ?>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
