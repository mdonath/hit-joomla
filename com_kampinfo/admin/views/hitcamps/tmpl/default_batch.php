<?php defined('_JEXEC') or die;

// Create the copy/move options.
$options = array(
	JHtml::_('select.option', 'akkoordPlaats', JText::_('Akkoord Plaats')),
	JHtml::_('select.option', 'nietAkkoordPlaats', JText::_('Geen akkoord Plaats')),
	JHtml::_('select.option', 'akkoordKamp', JText::_('Akkoord Kamp')),
	JHtml::_('select.option', 'nietAkkoordKamp', JText::_('Geen akkoord Kamp')),
);

if (JFactory::getUser()->authorise('hitproject.edit', 'com_kampinfo')) {
?>
<fieldset class="batch">
	<legend><?php echo JText::_('Bulk actie');?></legend>
	<fieldset id="batch-choose-action" class="combo">
		<?php echo JHtml::_('select.radiolist', $options, 'batch[group_action]', '', 'value', 'text', 'akkoordPlaats') ?>
	</fieldset>

	<button type="submit" onclick="Joomla.submitbutton('hitcamp.batch');">
		<?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
	</button>
</fieldset>
<?php }?>