<?php

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die('Restricted Access');

$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
?>

<thead>
	<tr>
		<td class="w-1 text-center">
			<?php echo HTMLHelper::_('grid.checkall'); ?>
		</td>
		<th  scope="col" class="w-1 text-center">
			<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?>
		</th>
		<th scope="col">
			<?php echo HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_NAME', 'naam', $listDirn, $listOrder); ?>
		</th>
		<th scope="col">
			Akkoord<br/>Kamp
		</th>
		<th scope="col">
			<?php echo HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_HITSITE', 'plaats', $listDirn, $listOrder); ?>
		</th>
		<th scope="col">
			Akkoord<br/>Plaats
		</th>
		<th scope="col">
			<?php echo HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_YEAR', 'jaar', $listDirn, $listOrder); ?>
		</th>
		<th scope="col">
			Min
		</th>
		<th scope="col">
			<?php echo HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_RESERVED', 'gereserveerd', $listDirn, $listOrder); ?>
		</th>
		<th scope="col">
			<?php echo HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_REGISTERED', 'aantalDeelnemers', $listDirn, $listOrder); ?>
		</th>
		<th scope="col">
			Max
		</th>
		<th scope="col">
			<?php echo HTMLHelper::_('searchtools.sort', 'Prijs', 'deelnamekosten', $listDirn, $listOrder); ?>
		</th>
		<th scope="col" class="w-1">
			<?php echo HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_HITCAMPS_HEADING_ID', 'id', $listDirn, $listOrder); ?>
		</th>
	</tr>
</thead>
