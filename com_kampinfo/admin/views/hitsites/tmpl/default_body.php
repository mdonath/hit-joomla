<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
?>
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td align="center">
			<?php $canPublish = $this->canDo->get($this->entityName .'.edit.state'); ?>
			<?php echo JHtml::_('jgrid.published', $item->published, $i, $this->entityName .'s.', $canPublish, 'cb');?>
		</td>
		<td>
			<?php if ($this->canDo->get($this->entityName.'.edit.'.(int)$item->id)) { ?>
				<a href="<?php echo JRoute :: _('index.php?option=com_kampinfo&task='.$this->entityName.'.edit&id='.(int)$item->id); ?>">
					<?php echo $item->naam; ?>
				</a>
			<?php } else { ?>
				<?php echo $item->naam; ?>
			<?php } ?>
		</td>
		<td>
			<?php echo $item->jaar; ?>
		</td>
		<td align="center">
			<?php $canEdit = ($this->canDo->get($this->entityName.'.edit.'.(int)$item->id)); ?>
			<?php echo JHtml::_('akkoord.akkoordplaats', $item->akkoordHitPlaats, $i, $this->entityName .'s.', $canEdit);?>
		</td>
		<td>
			<?php echo $item->contactPersoonNaam; ?>
		</td>
		<td>
			<?php echo $item->contactPersoonEmail; ?>
		</td>
		<td>
			<?php echo $item->contactPersoonTelefoon; ?>
		</td>
	</tr>
<?php endforeach; ?>