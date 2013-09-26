<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_kampinfo&layout=edit&id='.(int) $this->item->id); ?>"
      method="post"
      name="adminForm"
      id="hitsite-form"
>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_KAMPINFO_HITSITE_DETAILS' ); ?></legend>
		<ul class="adminformlist">
			<?php foreach($this->form->getFieldset("hitsite") as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
			<?php endforeach; ?>
		</ul>
	</fieldset>
	
	<!-- begin ACL definition-->
	<div class="clr"></div>
	<?php if ($this->canDo->get('core.admin')): ?>
	<div class="width-100 fltlft">
		<?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_KAMPINFO_FIELDSET_RULES'), 'access-rules'); ?>
		<fieldset class="panelform">
			<?php echo $this->form->getLabel('rules'); ?>
			<?php echo $this->form->getInput('rules'); ?>
		</fieldset>
		<?php echo JHtml::_('sliders.end'); ?>
	</div>
	<?php endif; ?>
	<!-- end ACL definition-->
	
	<div>
		<input type="hidden" name="task" value="hitsite.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>