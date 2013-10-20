<?php defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');

$fieldsets = $this->form->getFieldsets();
$user = JFactory::getUser();

?>
<form action="<?php echo JRoute::_('index.php?option=com_kampinfo&layout=edit&id='.(int) $this->item->id); ?>"
      method="post"
      name="adminForm"
      id="hitcamp-form"
>
	<div class="width-60 fltlft">
<?php 
		echo JHtml::_('sliders.start', 'hitcamp-left', array('useCookie'=>1));
		foreach ($fieldsets as $fieldset) {
			if ($fieldset->type == 'left') {
				echo JHtml::_('sliders.panel', JText::_($fieldset->label), $fieldset->name);
?>
		<fieldset class="adminform">
			<ul class="adminformlist">
				<?php foreach($this->form->getFieldset($fieldset->name) as $field): ?>
					<?php if ($field->hidden) { ?>
						<?php echo $field->input; ?>
					<?php } else { ?>
						<li><?php echo $field->label; ?> <?php echo $field->input; ?></li>
					<?php } ?>
				<?php endforeach; ?>
			</ul>
		</fieldset>
<?php
			}
		}
		echo JHtml::_('sliders.end');
?>
	</div>
	
	<div class="width-40 fltrt">
		<?php
		echo JHtml::_('sliders.start');
		foreach ($fieldsets as $fieldset) {
			$pos = strpos($fieldset->name, '@');
			if (!$pos || $this->canDo->get(substr($fieldset->name, $pos+1))) {
				if ($fieldset->type == 'right') {
					echo JHtml::_('sliders.panel', JText::_($fieldset->label), $fieldset->name);
				?>
					<fieldset class="panelform">
						<ul class="adminformlist">
							<?php foreach($this->form->getFieldset($fieldset->name) as $field) { ?>
								<?php
									$pos = strpos($field->name, '@');
									if (!$pos || $this->canDo->get(substr($field->name, $pos+1, -1))) { ?>
									<?php if ($field->hidden) { ?>
										<?php echo $field->input; ?>
									<?php } else { ?>
										<li><?php echo $field->label; ?> <?php echo $field->input; ?></li>
									<?php } ?>
								<?php }?>
							<?php } ?>
						</ul>
					</fieldset>
			<?php } ?>
		<?php } ?>
	<?php } ?>
		
	<?php echo JHtml::_('sliders.end'); ?>
		</div>


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