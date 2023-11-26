<?php

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$canDo = ContentHelper::getActions('com_kampinfo');
?>

<tbody>
    <?php foreach($this->items as $i => $item) : ?>
        <tr>
            <?php if ($this->canDo->get('hitproject.edit') || $this->canDo->get('hitproject.delete')) { ?>
                <td>
                    <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                </td>
            <?php } ?>
            <td>
                <?php if ($canDo->get('hitproject.edit')) { ?>
                    <a href="<?php echo Route::_('index.php?option=com_kampinfo&task=project.edit&id=' . (int)$item->id); ?>">
                        <?= $item->jaar ?>
                    </a>
                    <?php } else { ?>
                        <?= $item->jaar ?>
                    <?php } ?>
            </td>
            <td>
                <?php echo HTMLHelper::date($item->inschrijvingStartdatum, Text::_('COM_KAMPINFO_DATETIME_FORMAT')); ?>
            </td>
            <td>
                <?php echo HTMLHelper::date($item->inschrijvingEinddatum, Text::_('COM_KAMPINFO_DATETIME_FORMAT')); ?>
            </td>
            <td class="hidden-phone">
                <?php echo HTMLHelper::date($item->inschrijvingWijzigenTotDatum, Text::_('COM_KAMPINFO_DATE_FORMAT')); ?>
            </td>
            <td class="hidden-phone">
                <?php echo HTMLHelper::date($item->inschrijvingKosteloosAnnulerenDatum, Text::_('COM_KAMPINFO_DATE_FORMAT')); ?>
            </td>
            <td class="hidden-phone">
                <?php echo HTMLHelper::date($item->inschrijvingGeenRestitutieDatum, Text::_('COM_KAMPINFO_DATE_FORMAT')); ?>
            </td>
            <td class="hidden-phone">
                <?php echo HTMLHelper::date($item->inningsdatum, Text::_('COM_KAMPINFO_DATE_FORMAT')); ?>
            </td>
            <td class="hidden-phone">
                <?php echo $item->id; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
