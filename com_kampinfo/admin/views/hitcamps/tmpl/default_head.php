<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<tr>
	<th width="5">
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_ID', 'id', $listDirn, $listOrder); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_YEAR', 'jaar', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_HITSITE', 'plaats', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_NAME', 'naam', $listDirn, $listOrder); ?>
	</th>
		<?php /*echo JText::_('COM_KAMPINFO_HITCAMPS_HEADING_NAME');*/ ?>
</tr>