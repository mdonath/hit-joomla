<?php defined('_JEXEC') or die('Restricted Access');

$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
?>
<tr>
	<th width="20">
		<?php echo JHtml::_('grid.checkall'); ?>
	</th>
	<th>
		<?php echo JHtml::_('searchtools.sort', 'COM_KAMPINFO_HITPROJECTS_HEADING_YEAR', 'jaar', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JText::_('COM_KAMPINFO_HITPROJECTS_HEADING_INSCHRIJVING_START'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_KAMPINFO_HITPROJECTS_HEADING_INSCHRIJVING_EIND'); ?>
	</th>
	<th class="hidden-phone">
		<?php echo JText::_('COM_KAMPINFO_HITPROJECTS_HEADING_INSCHRIJVING_WIJZIGEN_TOT'); ?>
	</th>
	<th class="hidden-phone">
		<?php echo JText::_('COM_KAMPINFO_HITPROJECTS_HEADING_INSCHRIJVING_KOSTELOOS_ANNULEREN'); ?>
	</th>
	<th class="hidden-phone">
		<?php echo JText::_('COM_KAMPINFO_HITPROJECTS_HEADING_INSCHRIJVING_GEEN_RESTITIE'); ?>
	</th>
	<th class="hidden-phone">
		<?php echo JText::_('COM_KAMPINFO_HITPROJECTS_HEADING_INNINGSDATUM'); ?>
	</th>
	<th width="5" class="hidden-phone">
		<?php echo JText::_('COM_KAMPINFO_HITPROJECTS_HEADING_ID'); ?>
	</th>
</tr>