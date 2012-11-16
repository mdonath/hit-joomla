<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
?>

<form action="<?php echo JRoute::_('index.php?option=com_kampinfo&view=import'); ?>"
	enctype="multipart/form-data" 
	method="post"
	name="adminForm"
	id="adminForm"
>

	<fieldset class="adminform">
		<legend><?php echo JText::_('Import'); ?></legend>
		<ul class="adminformlist">
			<?php foreach($this->form->getFieldset("import") as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
			<?php endforeach; ?>
			<li>
				<label></label><input class="button" type="submit" value="<?php echo JText::_('Upload'); ?>" />
			</li>
		</ul>
	</fieldset>

	<div class="clr"></div>

	<div>
		<input type="hidden" name="task" value="import.import" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>