<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
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

<!-- START VIEW: hitproject/tmpl/edit.php -->
<form action="<?php echo JRoute::_('index.php?option=com_kampinfo&layout=edit&id='.(int) $this->item->id); ?>"
      method="post"
      name="adminForm"
      id="adminForm"
>
	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'hitproject')); ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'hitproject', JText::_('COM_KAMPINFO_HITPROJECT_DETAILS', true)); ?>
			<div class="row-fluid">
				<div class="span6">
					<?php pr_fieldset($this->form, ('hitproject')); ?>
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
			
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'standaardteksten', JText::_('Standaardteksten')); ?>
				<div class="row-fluid">
					<div class="span12">
						<?php pr_fieldset($this->form, ('standaardteksten')); ?>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
					<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>

	<div>
		<input type="hidden" name="task" value="hitproject.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<!-- END VIEW: hitproject/tmpl/edit.php -->
