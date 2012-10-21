<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder   = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
?>
<tr>
	<th width="5">
		<?php echo JText::_('COM_KAMPINFO_HITPROJECTS_HEADING_ID'); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_HITPROJECTS_HEADING_YEAR', 'jaar', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JText::_('COM_KAMPINFO_HITPROJECTS_HEADING_INSCHRIJVING_START'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_KAMPINFO_HITPROJECTS_HEADING_INSCHRIJVING_EIND'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_KAMPINFO_HITPROJECTS_HEADING_INSCHRIJVING_WIJZIGEN_TOT'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_KAMPINFO_HITPROJECTS_HEADING_INSCHRIJVING_KOSTELOOS_ANNULEREN'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_KAMPINFO_HITPROJECTS_HEADING_INSCHRIJVING_GEEN_RESTITIE'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_KAMPINFO_HITPROJECTS_HEADING_INNINGSDATUM'); ?>
	</th>
</tr>