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
				<?php $canPublish = true; ?>
				<?php echo HTMLHelper::_('jgrid.published', $item->published, $i, 'sites.', $canPublish, 'cb');?>
			</td>

			<td>
				<a href="<?php echo Route::_('index.php?option=com_kampinfo&task=site.edit&id=' . (int)$item->id); ?>">
				   <?= $item->naam ?>
				</a>
			</td>
			<td>
				<?php echo $item->jaar; ?>
			</td>
			<td>
				<?php $canEdit = true; ?>
				<?php echo HTMLHelper::_('akkoord.akkoordplaats', $item->akkoordHitPlaats, $i, 'sites.', $canEdit); ?>
			</td>
			<td>
				<?php echo $item->contactPersoonNaam; ?>
			</td>
			<td>
				<?php echo $item->contactPersoonEmail; ?>
			</td>
			<td>
				<?php echo $item->contactPersoonTelefoon; ?>
			</td>
			<td>
				<a href="<?php echo Route::_('index.php?option=com_kampinfo&view=site&format=pdf&id='.(int)$item->id); ?>">
						download
				</a>
			</td>
			<td>
				<?php echo $item->id; ?>
			</td>
		</tr>
	<?php endforeach; ?>
</tbody>
