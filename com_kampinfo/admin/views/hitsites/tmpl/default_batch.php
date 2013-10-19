<?php defined('_JEXEC') or die;

// Create the copy/move options.
$options = array(
	JHtml::_('select.option', '', JText::_('- Kies een actie -')),
	JHtml::_('select.option', 'akkoordPlaats', JText::_('Akkoord Plaats')),
	JHtml::_('select.option', 'nietAkkoordPlaats', JText::_('Geen akkoord Plaats')),
	JHtml::_('select.option', 'copyKampen', JText::_('Copieer kampen vorig jaar')),
);

if (JFactory::getUser()->authorise('hitproject.edit', 'com_kampinfo')) {
?>
<fieldset class="batch">
	<legend><?php echo JText::_('Bulk acties');?></legend>
	<fieldset id="batch-choose-action" class="combo">
		<select name="batch[group_action]" class="inputbox">
			<?php echo JHtml::_('select.options', $options, 'value', 'text', '', true) ?>
		</select>
	</fieldset>

	<button type="submit" onclick="Joomla.submitbutton('hitsite.batch');">
		<?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
	</button>
</fieldset>
<?php }?>