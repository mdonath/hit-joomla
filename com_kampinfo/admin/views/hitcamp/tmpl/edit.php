<?php defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');

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
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'hitsite', JText::_('COM_KAMPINFO_HITCAMP_DETAILS', true)); ?>
				<div class="row-fluid">
					<div class="span6">
						<?php pr_fieldset($this->form, ('hitcamp')); ?>
						<?php pr_fieldset($this->form, ('akkoordkamp')); ?>
						<?php if ($this->canDo->get('hitcamp.delete')) { ?>
							<?php pr_fieldset($this->form, ('akkoordplaats')); ?>
						<?php } ?>
					</div>
					<div class="span6">
						<?php pr_fieldset($this->form, ('helpdesk')); ?>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
			
			
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'courant', JText::_('HIT Courant')); ?>
				<div class="row-fluid">
					<div class="span12">
						<?php pr_fieldset($this->form, ('hitcourant')); ?>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'website', JText::_('Website')); ?>
				<div class="row-fluid">
					<div class="span6">
						<?php pr_fieldset($this->form, ('hitwebsite')); ?>
					</div>
					<div class="span6">
						<?php pr_fieldset($this->form, ('hitwebsiteextra')); ?>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'icons', JText::_('Iconen')); ?>
				<div class="row-fluid">
					<div class="span3">
						<?php pr_fieldset($this->form, ('iconen'), false); ?>
					</div>
					<div class="span3">
						<?php pr_fieldset($this->form, ('activiteitengebieden'), false); ?>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
			
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'deelnemer', JText::_('Deelnemer')); ?>
				<div class="row-fluid">
					<div class="span6">
						<?php pr_fieldset($this->form, ('leeftijd')); ?>
					</div>
					<div class="span6">
						<?php pr_fieldset($this->form, ('aantallen')); ?>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
			
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'doelstelling', JText::_('Doelstelling')); ?>
				<fieldset class="form-vertical">
					<?php pr_fieldset($this->form, ('doelstelling')); ?>
				</fieldset>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'financien', JText::_('HIT Financiën')); ?>
				<fieldset class="form-vertical">
					<?php pr_fieldset($this->form, ('financien')); ?>
				</fieldset>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
			
			<?php if ($this->canDo->get('core.admin')) { ?>
				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'admin', JText::_('Admin')); ?>
					<div class="row-fluid">
						<div class="span6">
							<?php pr_fieldset($this->form, ('publish')); ?>
							<?php pr_fieldset($this->form, ('inschrijvingen')); ?>
						</div>
					</div>
				<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php } ?>

			<?php if ($this->canDo->get('core.admin')) { ?>
				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'rules', JText::_('COM_KAMPINFO_FIELDSET_RULES')); ?>
					<?php echo $this->form->getInput('rules'); ?>
				<?php echo JHtml::_('bootstrap.endTab'); ?>	 	 
			<?php } ?>
						
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>

	<div>
		<input type="hidden" name="task" value="hitcamp.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
