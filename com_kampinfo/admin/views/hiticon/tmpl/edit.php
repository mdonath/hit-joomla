<?php defined('_JEXEC') or die('Restricted access');
JHtmlBootstrap::tooltip();
?>
<form	action="<?php echo JRoute::_('index.php?option=com_kampinfo&layout=edit&id='.(int) $this->item->id); ?>"
		method="post"
		name="adminForm"
		id="adminForm"
>
	<div class="form-horizontal">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_KAMPINFO_HITICON_DETAILS' ); ?></legend>
			<div class="row-fluid">
				<div class="span6">
					<?php foreach($this->form->getFieldset("hiticon") as $field): ?>
						<div class="control-group">
							<div class="control-label"><?php echo $field->label; ?></div>
							<div class="controls"><?php echo $field->input; ?></div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</fieldset>
	</div>
	<div>
		<input type="hidden" name="task" value="hiticon.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>