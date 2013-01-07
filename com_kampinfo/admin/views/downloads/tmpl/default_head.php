<?php defined('_JEXEC') or die('Restricted Access');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$this->state->set('list.columnCount', '6');
?>
<tr>
	<th width="5">
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_DOWNLOADS_HEADING_ID', 'id', $listDirn, $listOrder); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_DOWNLOADS_HEADING_YEAR', 'jaar', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_DOWNLOADS_HEADING_SOORT', 'soort', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_DOWNLOADS_HEADING_BIJGEWERKTOP', 'bijgewerktOp', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_DOWNLOADS_HEADING_MELDING', 'melding', $listDirn, $listOrder); ?>
	</th>
</tr>