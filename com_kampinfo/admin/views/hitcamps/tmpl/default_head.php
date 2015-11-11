<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

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
		<?php echo JHtml::_('searchtools.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_NAME', 'naam', $listDirn, $listOrder); ?>
	</th>
	<th>
		Akkoord<br/>Kamp
	</th>
	<th>
		<?php echo JHtml::_('searchtools.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_HITSITE', 'plaats', $listDirn, $listOrder); ?>
	</th>
	<th>
		Akkoord<br/>Plaats
	</th>
	<th>
		<?php echo JHtml::_('searchtools.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_YEAR', 'jaar', $listDirn, $listOrder); ?>
	</th>
	<th class="hidden-phone">
		Min
	</th>
	<th class="hidden-phone">
		<?php echo JHtml::_('searchtools.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_RESERVED', 'gereserveerd', $listDirn, $listOrder); ?>
	</th>
	<th class="hidden-phone">
		<?php echo JHtml::_('searchtools.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_REGISTERED', 'aantalDeelnemers', $listDirn, $listOrder); ?>
	</th>
	<th class="hidden-phone">
		Max
	</th>
	<th class="hidden-phone">
		<?php echo JHtml::_('searchtools.sort', 'Prijs', 'deelnamekosten', $listDirn, $listOrder); ?>
	</th>
	<th class="hidden-phone">
		#Mdw
	</th>
	<th width="5" class="hidden-phone">
		<?php echo JHtml::_('searchtools.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_ID', 'id', $listDirn, $listOrder); ?>
	</th>
</tr>