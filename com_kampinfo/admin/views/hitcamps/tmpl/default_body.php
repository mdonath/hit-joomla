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
			<?php $canPublish = $this->canDo->get($this->entityName .'.edit.state'); ?>
			<?php echo JHtml::_('jgrid.published', $item->published, $i, $this->entityName .'s.', $canPublish, 'cb');?>
		</td>
		<td>
			<?php if ($this->canDo->get($this->entityName.'.edit.'.(int)$item->id)) { ?>
				<a href="<?php echo JRoute :: _('index.php?option=com_kampinfo&task='.$this->entityName.'.edit&id='.(int)$item->id); ?>">
					<?php echo $item->naam; ?>
				</a>
			<?php } else { ?>
				<?php echo $item->naam; ?>
			<?php } ?>
		</td>
		<td align="center">
			<?php echo JHtml::_('jgrid.published', $item->akkoordHitKamp, $i, $this->entityName .'s.', false, 'cb');?>
		</td>
		<td>
			<?php echo $item->plaats; ?>
		</td>
		<td align="center">
			<?php echo JHtml::_('jgrid.published', $item->akkoordHitPlaats, $i, $this->entityName .'s.', false, 'cb');?>
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