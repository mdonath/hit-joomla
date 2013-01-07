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
			<?php echo JText::_($item->jaar); ?>
		</td>
		<td>
			<?php echo JText::_($item->soort); ?>
		</td>
		<td>
			<?php echo JText::_($item->bijgewerktOp); ?>
		</td>
		<td>
			<?php echo JText::_($item->melding); ?>
		</td>
	</tr>
<?php endforeach; ?>