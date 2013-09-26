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
		<td>
			<?php if ($this->canDo->get('hitproject.edit')) { ?>
				<a href="<?php echo JRoute :: _('index.php?option=com_kampinfo&task=hitproject.edit&id='.(int)$item->hitproject_id); ?>">
					<?php echo $item->jaar; ?>
				</a>
			<?php } else { ?>
				<?php echo $item->jaar; ?>
			<?php } ?>
		</td>
		<td>
			<?php if ($this->canDo->get('hitsite.edit')) { ?>
				<a href="<?php echo JRoute :: _('index.php?option=com_kampinfo&task=hitsite.edit&id='.(int)$item->hitsite_id); ?>">
					<?php echo $item->plaats; ?>
				</a>
			<?php } else { ?>
				<?php echo $item->plaats; ?>
			<?php } ?>
		</td>
		<td>
			<?php echo JText::_($item->deelnemersnummer); ?>
		</td>
		<td>
			<?php echo JText::_($item->shantiFormuliernummer); ?>
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
		<td>
			<?php echo JText::_($item->gereserveerd); ?>
		</td>
				<td>
			<?php echo JText::_($item->aantalDeelnemers); ?>
		</td>
		<td>
			<?php $canPublish = $this->canDo->get('hitcamp.edit.state'); ?>
			<?php echo JHtml::_('jgrid.published', $item->published, $i, 'hitcamps.', $canPublish, 'cb', $item->publish_up, $item->publish_down);?>
		</td>
	</tr>
<?php endforeach; ?>