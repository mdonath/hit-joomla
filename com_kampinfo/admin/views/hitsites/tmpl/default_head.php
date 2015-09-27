<?php defined('_JEXEC') or die('Restricted Access');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<tr>
	<th width="20">
		<?php echo JHtml::_('grid.checkall'); ?>
	</th>
	<th width="5%">
		<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('searchtools.sort', 'COM_KAMPINFO_HITSITES_HEADING_NAAM', 'naam', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('searchtools.sort', 'COM_KAMPINFO_HITSITES_HEADING_YEAR', 'jaar', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JText::_('COM_KAMPINFO_HITSITES_HEADING_AKKOORD'); ?>
	</th>
	<th class="hidden-phone">
		<?php echo JText::_('COM_KAMPINFO_HITSITES_HEADING_CONTACTPERSOON_NAAM'); ?>
	</th>
	<th class="hidden-phone">
		<?php echo JText::_('COM_KAMPINFO_HITSITES_HEADING_CONTACTPERSOON_EMAIL'); ?>
	</th>
	<th class="hidden-phone">
		<?php echo JText::_('COM_KAMPINFO_HITSITES_HEADING_CONTACTPERSOON_TELEFOON'); ?>
	</th>
	<th class="hidden-phone">
		<?php echo "PDF"; ?>
	</th>
	<th width="5" class="hidden-phone">
		<?php echo JHtml::_('searchtools.sort', 'COM_KAMPINFO_HITSITES_HEADING_ID', 'id', $listDirn, $listOrder); ?>
	</th>
</tr>