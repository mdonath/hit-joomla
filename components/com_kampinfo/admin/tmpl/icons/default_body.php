<?php

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\HTML\HTMLHelper;

use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;


$options = KampInfoHelper::getHitIconSoortOptions();

$user = $this->getCurrentUser();
$canEdit = $user->authorise('hiticon.edit', 'com_kampinfo');
?>

<tbody>
    <?php foreach($this->items as $i => $item) : ?>
        <tr>
            <td>
                <?= HTMLHelper::_('grid.id', $i, $item->id) ?>
            </td>
            <td>
                <?= $item->volgorde ?>
            </td>
            <td>
                <?= HTMLHelper::_('icoon.image', $item, 'large') ?>
            </td>
            <td>
                <?= HTMLHelper::_('icoon.naam', $item, $canEdit) ?>
            </td>
            <td class="hidden-phone">
                <?= $item->tekst ?>
            </td>
            <td class="hidden-phone">
                <?= $item->uitleg ?>
            </td>
            <td>
                <?= $options[$item->soort] ?>
            </td>
            <td class="hidden-phone">
                <?= $item->id ?>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
