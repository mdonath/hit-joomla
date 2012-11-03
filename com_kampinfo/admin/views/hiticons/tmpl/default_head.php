<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$this->state->set('list.columnCount', '6');
?>
<tr>
	<th width="5">
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_HITICONS_HEADING_ID', 'id', $listDirn, $listOrder); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_HITICONS_HEADING_VOLGORDE', 'volgorde', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JText::_('COM_KAMPINFO_HITICONS_HEADING_BESTANDSNAAM'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_KAMPINFO_HITICONS_HEADING_TEKST'); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_HITICONS_HEADING_SOORT', 'soort', $listDirn, $listOrder); ?>
	</th>
</tr>