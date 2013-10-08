<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$this->state->set('list.columnCount', '12');
?>
<tr>
	<th width="5">
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_ID', 'id', $listDirn, $listOrder); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>
	<th width="5%">
		<?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_NAME', 'naam', $listDirn, $listOrder); ?>
	</th>
	<th>
		Akkoord<br/>Kamp
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_HITSITE', 'plaats', $listDirn, $listOrder); ?>
	</th>
	<th>
		Akkoord<br/>Plaats
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_YEAR', 'jaar', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_DEELNEMERSNR', 'deelnemersnummer', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_SHANTIFORM', 'shantiFormuliernummer', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_RESERVED', 'gereserveerd', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_REGISTERED', 'aantalDeelnemers', $listDirn, $listOrder); ?>
	</th>
</tr>