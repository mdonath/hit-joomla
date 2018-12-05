<?php defined('_JEXEC') or die('Restricted Access');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<tr>
	<th width="20">
		<?php echo JHtml::_('grid.checkall'); ?>
	</th>
	<th>
		<?php echo JHtml::_('searchtools.sort', 'COM_KAMPINFO_HITICONS_HEADING_VOLGORDE', 'volgorde', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JText::_('COM_KAMPINFO_HITICONS_HEADING_BESTANDSNAAM'); ?>
	</th>
	<th class="hidden-phone">
		<?php echo JText::_('COM_KAMPINFO_HITICONS_HEADING_TEKST'); ?>
	</th>
	<th class="hidden-phone">
		<?php echo JText::_('COM_KAMPINFO_HITICONS_HEADING_UITLEG'); ?>
	</th>
	<th>
		<?php echo JHtml::_('searchtools.sort', 'COM_KAMPINFO_HITICONS_HEADING_SOORT', 'soort', $listDirn, $listOrder); ?>
	</th>
	<th width="5" class="hidden-phone">
		<?php echo JHtml::_('searchtools.sort', 'COM_KAMPINFO_HITICONS_HEADING_ID', 'id', $listDirn, $listOrder); ?>
	</th>
</tr>