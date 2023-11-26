<?php

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die('Restricted Access');
?>

<tbody>
    <?php foreach($this->items as $i => $item) : ?>
        <tr>
            <td>
                <?= HTMLHelper::_('grid.id', $i, $item->id) ?>
            </td>
            <td>
                <?php $canPublish = $this->canDo->get('hitcamp.edit.state'); ?>
                <?= HTMLHelper::_('jgrid.published', $item->published, $i, 'camps.', $canPublish, 'cb') ?>
            </td>
            <td>
                <?php 
                    $toonLink = $this->canDo->get('hitcamp.edit.'.(int)$item->id);
                    // if ($item->akkoordHitPlaats) {
                    //     $toonLink = $this->canDo->get('hitsite.edit.'.(int)$item->hitsite_id);
                    // }
                ?>
                <?php if ($toonLink) { ?>
                    <a href="<?php echo Route::_('index.php?option=com_kampinfo&task=camp.edit&id='.(int)$item->id); ?>">
                        <?= $item->naam ?>
                    </a>
                <?php } else { ?>
                    <?= $item->naam ?>
                <?php } ?>
            </td>
            <td>
                <?php $canEdit = ($this->canDo->get('hitcamp.edit.'.(int)$item->id)); ?>
                <?= HTMLHelper::_('akkoord.akkoordkamp', $item->akkoordHitKamp, $i, 'camps.', $canEdit) ?>
            </td>
            <td>
                <?= $item->plaats ?>
            </td>
            <td>
                <?php $canEdit = ($this->canDo->get('hitsite.edit.'.(int)$item->hitsite_id)); ?>
                <?= HTMLHelper::_('akkoord.akkoordplaats', $item->akkoordHitPlaats, $i, 'camps.', $canEdit) ?>
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
