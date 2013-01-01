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
			<a href="<?php echo JRoute :: _('index.php?option=com_kampinfo&task=hitproject.edit&id='.(int)$item->hitproject_id); ?>">
				<?php echo $item->jaar; ?>
			</a>
		</td>
		<td>
			<a href="<?php echo JRoute :: _('index.php?option=com_kampinfo&task=hitsite.edit&id='.(int)$item->hitsite_id); ?>">
				<?php echo $item->plaats; ?>
			</a>
		</td>
		<td>
			<?php echo JText::_($item->deelnemersnummer); ?>
		</td>
		<td>
			<?php echo JText::_($item->shantiFormuliernummer); ?>
		</td>
		<td>
			<a href="<?php echo JRoute :: _('index.php?option=com_kampinfo&task=hitcamp.edit&id='.(int)$item->id); ?>">
				<?php echo $item->naam; ?>
			</a>
		</td>
		<td>
			<?php echo JText::_($item->gereserveerd); ?>
		</td>
				<td>
			<?php echo JText::_($item->aantalDeelnemers); ?>
		</td>
	</tr>
<?php endforeach; ?>