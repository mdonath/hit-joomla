<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_kampinfo&layout=edit&id='.(int) $this->item->id); ?>"
      method="post"
      name="adminForm"
      id="hitcamp-form"
>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_KAMPINFO_INSCHRIJVINGEN_DETAILS' ); ?></legend>
		<ul class="adminformlist">
			<?php foreach($this->form->getFieldset('inschrijvingen') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
			<?php endforeach; ?>
		</ul>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_KAMPINFO_AKKOORD_DETAILS' ); ?></legend>
		<ul class="adminformlist">
			<?php foreach($this->form->getFieldset('akkoorden') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
			<?php endforeach; ?>
		</ul>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_KAMPINFO_HITCAMP_DETAILS' ); ?></legend>
		<ul class="adminformlist">
			<?php foreach($this->form->getFieldset('hitcamp') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
			<?php endforeach; ?>
		</ul>
	</fieldset>
	
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_KAMPINFO_HITCAMP_SITE_DETAILS' ); ?></legend>
		<ul class="adminformlist">
			<?php foreach($this->form->getFieldset('siteEnCourant') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
			<?php endforeach; ?>
		</ul>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_KAMPINFO_HELPDESK_DETAILS' ); ?></legend>
		<ul class="adminformlist">
			<?php foreach($this->form->getFieldset('helpdesk') as $field): ?>
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
		<input type="hidden" name="task" value="hitcamp.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>