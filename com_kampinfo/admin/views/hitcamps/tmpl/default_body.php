<?php defined('_JEXEC') or die('Restricted Access');
?>

<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td align="center">
			<?php $canPublish = $this->canDo->get('hitcamp.edit.state'); ?>
			<?php echo JHtml::_('jgrid.published', $item->published, $i, 'hitcamps.', $canPublish, 'cb');?>
		</td>
		<td>
			<?php if ($this->canDo->get('hitcamp.edit')) { ?>
				<a href="<?php echo JRoute :: _('index.php?option=com_kampinfo&task=hitcamp.edit&id='.(int)$item->id); ?>">
					<?php echo $item->naam; ?>
				</a>
			<?php } else { ?>
				<?php echo $item->naam; ?>
			<?php } ?>
		</td>
		<td align="center">
			<?php echo JHtml::_('jgrid.published', $item->akkoordHitKamp, $i, 'hitcamps.', false, 'cb');?>
		</td>
		<td>
			<?php echo $item->plaats; ?>
		</td>
		<td align="center">
			<?php echo JHtml::_('jgrid.published', $item->akkoordHitPlaats, $i, 'hitcamps.', false, 'cb');?>
		</td>
		<td>
			<?php echo $item->jaar; ?>
		</td>
		<td>
			<?php echo JText::_($item->deelnemersnummer); ?>
		</td>
		<td>
			<?php echo JText::_($item->shantiFormuliernummer); ?>
		</td>
		<td>
			<?php echo JText::_($item->gereserveerd); ?>
		</td>
				<td>
			<?php echo JText::_($item->aantalDeelnemers); ?>
		</td>
	</tr>
<?php endforeach; ?>