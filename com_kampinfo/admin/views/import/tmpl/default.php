<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
?>

<form action="<?php echo JRoute::_('index.php?option=com_kampinfo&view=import'); ?>"
	enctype="multipart/form-data" 
	method="post"
	name="adminForm"
	id="adminFormInschrijving"
>

	<fieldset class="adminform">
		<legend><?php echo JText::_('Importeer Inschrijvingen'); ?></legend>
		<ul class="adminformlist">
			<?php foreach($this->form->getFieldset("importInschrijvingen") as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
			<?php endforeach; ?>
			<li>
				<label></label><input class="button" type="submit" value="<?php echo JText::_('Upload'); ?>" />
			</li>
		</ul>
	</fieldset>

	
	<div>
		<input type="hidden" name="task" value="import.importInschrijvingen" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

</form>

<!-- 
<form action="<?php echo JRoute::_('index.php?option=com_kampinfo&view=import'); ?>"
	enctype="multipart/form-data" 
	method="post"
	name="adminForm"
	id="adminFormDeelnemers"
>

	<fieldset class="adminform">
		<legend><?php echo JText::_('Importeer Deelnemers'); ?></legend>
		<ul class="adminformlist">
			<?php foreach($this->form->getFieldset("importDeelnemers") as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
			<?php endforeach; ?>
			<li>
				<label></label><input class="button" type="submit" value="<?php echo JText::_('Upload'); ?>" />
			</li>
		</ul>
	</fieldset>

	
	<div>
		<input type="hidden" name="task" value="import.importDeelnemers" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

</form>
 -->


<form action="<?php echo JRoute::_('index.php?option=com_kampinfo&view=import'); ?>"
	enctype="multipart/form-data" 
	method="post"
	name="adminForm"
	id="adminFormInschrijvingDownload"
>

	<fieldset class="adminform">
		<legend><?php echo JText::_('Download en importeer uit SOL Inschrijfgegevens'); ?></legend>
		<ul class="adminformlist">
			<li>
				<label></label><input class="button" type="submit" value="<?php echo JText::_('Download en importeer Inschrijvingen'); ?>" />
			</li>
		</ul>
	</fieldset>

	
	<div>
		<input type="hidden" name="task" value="import.downloadInschrijvingen" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

</form>

<form action="<?php echo JRoute::_('index.php?option=com_kampinfo&view=import'); ?>"
	enctype="multipart/form-data" 
	method="post"
	name="adminForm"
	id="adminFormDeelnemersDownload"
>

	<fieldset class="adminform">
		<legend><?php echo JText::_('Download en importeer uit SOL Deelnemersgegevens'); ?></legend>
		<ul class="adminformlist">
			<li>
				<label></label><input class="button" type="submit" value="<?php echo JText::_('Download en importeer Deelnemers'); ?>" />
			</li>
		</ul>
	</fieldset>

	
	<div>
		<input type="hidden" name="task" value="import.downloadDeelnemers" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

</form>
