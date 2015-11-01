<?php defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

function pr_field($field, $horizontal = true) {
	return <<<EOD
<div class="control-group">
	<div class="control-label">{$field->label}</div>
	<div class="controls">{$field->input}</div>
</div>
EOD;
}

function pr_fieldset($form, $fieldsetName, $horizontal = true) {
	$orientation = $horizontal ? 'horizontal' : 'vertical';
	echo "<fieldset class=\"form-{$orientation}\">";
	$label = $form->getFieldsets()[$fieldsetName]->label;
	if ($label != '') {
		echo "<legend>{$label}</legend>";
	}

	foreach($form->getFieldset($fieldsetName) as $field):
		if ($field->hidden) {
			echo $field->input;
		} else {
			echo pr_field($field, $horizontal);
		}
	endforeach;
	echo '</fieldset>';
}
?>
<form	action="<?php echo JRoute::_('index.php?option=com_kampinfo&layout=edit&id='.(int) $this->item->id); ?>"
		method="post"
		name="adminForm"
		id="adminForm"
>
	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'hitsite')); ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'hitsite', JText::_('COM_KAMPINFO_HITSITE_DETAILS', true)); ?>
				<div class="row-fluid">
					<div class="span8">
						<fieldset class="form-horizontal">
						<?php foreach($this->form->getFieldset("hitsite") as $field) { ?>
							<div class="control-group">
								<div class="control-label"><?php echo $field->label; ?></div>
								<div class="controls"><?php echo $field->input; ?></div>
							</div>
						<?php } ?>
						</fieldset>
					</div>
					<div class="span4">
						<fieldset class="form-horizontal">
						<?php foreach($this->form->getFieldset("akkoorden") as $field) { ?>
							<div class="control-group">
								<div class="control-label"><?php echo $field->label; ?></div>
								<div class="controls"><?php echo $field->input; ?></div>
							</div>
						<?php } ?>
						</fieldset>
						<div class="control-group">
							<div class="control-label">Kopieer kampen van vorig jaar</div>
							<div class="controls"><input type="button" value="Kopieer" onclick="Joomla.submitbutton('hitsite.copyCamps');"/></div>
						</div>
						<?php if ($this->canDo->get('core.admin')) { ?>
							<?php foreach($this->form->getFieldset("financienProject") as $field) { ?>
								<div class="control-group">
									<div class="control-label"><?php echo $field->label; ?></div>
									<div class="controls"><?php echo $field->input; ?></div>
								</div>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'aanlevering', JText::_('HIT Courant')); ?>
				<div class="row-fluid">
					<div class="span12">
						<?php foreach($this->form->getFieldset("aanlevering") as $field) { ?>
							<div class="control-group">
								<div class="control-label"><?php echo $field->label; ?></div>
								<div class="controls"><?php echo $field->input; ?></div>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
			
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'financien', JText::_('HIT Financien')); ?>
				<div class="row-fluid">
					<div class="span12">
						<?php pr_fieldset($this->form, ('financien')); ?>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
			
			<?php if ($this->canDo->get('core.admin')) { ?>
				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'rules', JText::_('COM_KAMPINFO_FIELDSET_RULES')); ?>
					<?php echo $this->form->getInput('rules'); ?>
				<?php echo JHtml::_('bootstrap.endTab'); ?>	 	 
			<?php } ?>
				
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
		
	</div>
	
	<div>
		<input type="hidden" id="task" name="task" value="hitsite.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>