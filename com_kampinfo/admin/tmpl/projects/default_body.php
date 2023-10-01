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
				<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
			</td>
			<td>
				<a href="<?php echo Route::_('index.php?option=com_kampinfo&task=project.edit&id=' . (int)$item->id); ?>">
				   <?= $item->jaar ?>
				</a>
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