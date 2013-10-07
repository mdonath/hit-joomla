<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td>
			<?php echo $item->volgorde; ?>
		</td>
		<td>
			<?php if ($this->canDo->get('hiticon.edit')) { ?>
				<a href="<?php echo JRoute :: _('index.php?option=com_kampinfo&task=hiticon.edit&id='.(int)$item->id); ?>">
					<?php echo $item->bestandsnaam; ?>
				</a>
			<?php } else { ?>
				<?php echo $item->bestandsnaam; ?>
			<?php } ?>
		</td>
		<td>
			<?php echo $item->tekst; ?>
		</td>
		<td>
			<?php
			require_once JPATH_COMPONENT_ADMINISTRATOR.'/../com_kampinfo/helpers/kampinfo.php';
			$options = KampInfoHelper :: getHitIconSoortOptions(); 
			echo $options[$item->soort];
			?>
		</td>
	</tr>
<?php endforeach; ?>