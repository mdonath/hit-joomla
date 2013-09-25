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
	
	<div>
		<input type="hidden" name="task" value="hitcamp.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>