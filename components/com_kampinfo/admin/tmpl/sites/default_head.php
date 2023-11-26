<?php

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

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
			<?php echo HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_HITSITES_HEADING_NAAM', 'naam', $listDirn, $listOrder); ?>
		</th>
		<th scope="col">
			<?php echo HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_HITSITES_HEADING_YEAR', 'jaar', $listDirn, $listOrder); ?>
		</th>
		<th scope="col">
			<?php echo Text::_('COM_KAMPINFO_HITSITES_HEADING_AKKOORD'); ?>
		</th>
		<th scope="col">
			<?php echo Text::_('COM_KAMPINFO_HITSITES_HEADING_CONTACTPERSOON_NAAM'); ?>
		</th>
		<th scope="col">
			<?php echo Text::_('COM_KAMPINFO_HITSITES_HEADING_CONTACTPERSOON_EMAIL'); ?>
		</th>
		<th scope="col">
			<?php echo Text::_('COM_KAMPINFO_HITSITES_HEADING_CONTACTPERSOON_TELEFOON'); ?>
		</th>
		<th scope="col">
			<?php echo "PDF"; ?>
		</th>
		<th scope="col" class="w-1">
			<?php echo HTMLHelper::_('searchtools.sort', 'COM_KAMPINFO_HITSITES_HEADING_ID', 's.id', $listDirn, $listOrder); ?>
		</th>
	</tr>
</thead>
