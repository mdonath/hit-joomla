<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$this->state->set('list.columnCount', '10');
?>
<tr>
	<th width="5">
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_HITSITES_HEADING_ID', 'id', $listDirn, $listOrder); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_HITSITES_HEADING_YEAR', 'jaar', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_HITSITES_HEADING_NAAM', 'naam', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JText::_('COM_KAMPINFO_HITSITES_HEADING_AKKOORD'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_KAMPINFO_HITSITES_HEADING_DEELNEMERSNUMMER'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_KAMPINFO_HITSITES_HEADING_HITCOURANTTEKST'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_KAMPINFO_HITSITES_HEADING_CONTACTPERSOON_NAAM'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_KAMPINFO_HITSITES_HEADING_CONTACTPERSOON_EMAIL'); ?>
	</th>
	<th>
		<?php echo JText::_('COM_KAMPINFO_HITSITES_HEADING_CONTACTPERSOON_TELEFOON'); ?>
	</th>
</tr>