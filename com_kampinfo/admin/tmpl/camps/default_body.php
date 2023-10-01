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
				<?php echo HTMLHelper::_('jgrid.published', $item->published, $i, 'camps.', $canPublish, 'cb');?>
			</td>
			<td>
				<?php 
					$toonLink = true; // $this->canDo->get($this->entityName.'.edit.'.(int)$item->id);
					if ($item->akkoordHitPlaats) {
						$toonLink = false;
						//if ($this->canDo->get('hitsite.edit.'.(int)$item->hitsite_id)) {
							$toonLink = true;
						//}
					}
				?>
				<?php if ($toonLink) { ?>
					<a href="<?php echo Route::_('index.php?option=com_kampinfo&task=camp.edit&id='.(int)$item->id); ?>">
						<?php echo $item->naam; ?>
					</a>
				<?php } else { ?>
					<?php echo $item->naam; ?>
				<?php } ?>
			</td>
			<td>
				<?php $canEdit = true; //($this->canDo->get($this->entityName.'.edit.'.(int)$item->id)); ?>
				<?php echo HTMLHelper::_('akkoord.akkoordkamp', $item->akkoordHitKamp, $i, 'camps.', $canEdit);?>
			</td>
			<td>
				<?php echo $item->plaats; ?>
			</td>
			<td>
				<?php $canEdit = true; // ($this->canDo->get('hitcamp.edit.'.(int)$item->hitsite_id)); ?>
				<?php echo HTMLHelper::_('akkoord.akkoordplaats', $item->akkoordHitPlaats, $i, 'camps.', $canEdit);?>
			</td>
			<td>
				<?php echo $item->jaar; ?>
			</td>
			<td>
				<?php echo Text::_($item->minimumAantalDeelnemers); ?>
			</td>
			<td>
				<?php echo Text::_($item->gereserveerd); ?>
			</td>
			<td>
				<?php echo Text::_($item->aantalDeelnemers); ?>
			</td>
			<td>
				<?php echo Text::_($item->maximumAantalDeelnemers); ?> (<?php echo Text::_($item->maximumAantalDeelnemersOrigineel); ?>)
			</td>
			<td>
				€ <?php echo Text::_($item->deelnamekosten); ?>
			</td>
			<td>
				<?php echo $item->id; ?>
			</td>
		</tr>
	<?php endforeach; ?>
</tbody>
