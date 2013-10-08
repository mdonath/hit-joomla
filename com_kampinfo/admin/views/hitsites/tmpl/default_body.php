<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td>
			<?php if ($this->canDo->get('hitsite.edit.'.(int)$item->id)) { ?>
				<a href="<?php echo JRoute :: _('index.php?option=com_kampinfo&task=hitsite.edit&id='.(int)$item->id); ?>">
					<?php echo $item->naam; ?>
				</a>
			<?php } else { ?>
				<?php echo $item->naam; ?>
			<?php } ?>
		</td>
		<td>
			<?php echo $item->jaar; ?>
		</td>
		<td align="center">
			<?php echo JHtml::_('jgrid.published', $item->akkoordHitPlaats, $i, 'hitcamps.', false, 'cb');?>
		</td>
		<td>
			<?php echo $item->deelnemersnummer; ?>
		</td>
		<td>
			<?php echo $item->hitCourantTekst; ?>
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
	</tr>
<?php endforeach; ?>