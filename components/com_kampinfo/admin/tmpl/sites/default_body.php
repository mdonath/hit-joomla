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
            $canEdit    = $user->authorise('hitsite.edit', 'com_kampinfo.site.' . (int)$item->id);
            $canPublish = $user->authorise('hitsite.edit.state', 'com_kampinfo.site.' . (int)$item->id);
        ?>
        <tr>
            <td>
                <?= HTMLHelper::_('grid.id', $i, $item->id) ?>
            </td>
            <td>
                <?= HTMLHelper::_('jgrid.published', $item->published, $i, 'sites.', $canPublish, 'cb') ?>
            </td>
            <td>
                <?= HTMLHelper::_('plaats.naam', $item, $canEdit) ?>
            </td>
            <td>
                <?= $item->jaar ?>
            </td>
            <td>
                <?= HTMLHelper::_('akkoord.akkoordplaats', $item->akkoordHitPlaats, $i, 'sites.', $canEdit) ?>
            </td>
            <td>
                <?= $item->contactPersoonNaam ?>
            </td>
            <td>
                <?= $item->contactPersoonEmail ?>
            </td>
            <td>
                <?= $item->contactPersoonTelefoon ?>
            </td>
            <td>
                <?= HTMLHelper::_('plaats.downloadPdf', $item, $canEdit) ?>
            </td>
            <td>
                <?= $item->id ?>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
