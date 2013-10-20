<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<form action="<?php echo JRoute::_('index.php?option=com_kampinfo&layout=edit&id='.(int) $this->item->id); ?>"
      method="post"
      name="adminForm"
      id="hitsite-form"
>
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_KAMPINFO_HITSITE_DETAILS' ); ?></legend>
			<ul class="adminformlist">
				<?php foreach($this->form->getFieldset("hitsite") as $field) { ?>
					<li><?php echo $field->label;echo $field->input;?></li>
				<?php } ?>
			</ul>
		</fieldset>
		
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'Contactgegevens (uitsluitend voor helpdesk)' ); ?></legend>
			<ul class="adminformlist">
				<?php foreach($this->form->getFieldset("contact") as $field) { ?>
					<li><?php echo $field->label;echo $field->input;?></li>
				<?php } ?>
			</ul>
		</fieldset>
		
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'Aanlevering' ); ?></legend>
			<ul class="adminformlist">
				<?php foreach($this->form->getFieldset("aanlevering") as $field) { ?>
					<li><?php echo $field->label;echo $field->input;?></li>
				<?php } ?>
			</ul>
		</fieldset>
	</div>
	
	<div class="width-40 fltrt">
		<fieldset class="panelform" >
			<legend><?php echo JText::_( 'Akkoorden' ); ?></legend>
			<ul class="adminformlist">
				<?php foreach($this->form->getFieldset("akkoorden") as $field) { ?>
					<li><?php echo $field->label;echo $field->input;?></li>
				<?php } ?>
			</ul>
		</fieldset>
		<fieldset class="panelform" >
			<legend><?php echo JText::_( 'Kopieer van vorig jaar' ); ?></legend>
			<ul class="adminformlist">
				<li><label>Kopieer kampen van vorig jaar</label><input type="button" value="Kopieer" onclick="Joomla.submitbutton('hitsite.copyCamps');"/></li>
			</ul>
		</fieldset>
	</div>
   
	<!-- begin ACL definition-->
	<div class="clr"></div>
	
	<?php if ($this->canDo->get('core.admin')) { ?>
	<div class="width-100 fltlft">
		<?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_KAMPINFO_FIELDSET_RULES'), 'access-rules'); ?>
		<fieldset class="panelform">
			<?php echo $this->form->getLabel('rules'); ?>
			<?php echo $this->form->getInput('rules'); ?>
		</fieldset>
		<?php echo JHtml::_('sliders.end'); ?>
	</div>
	<?php } ?>
	<!-- end ACL definition-->
	
	<div>
		<input type="hidden" id="task" name="task" value="hitsite.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>