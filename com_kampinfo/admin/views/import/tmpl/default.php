<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
?>

<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">


<form action="<?php echo JRoute::_('index.php?option=com_kampinfo&view=import'); ?>"
	enctype="multipart/form-data" 
	method="post"
	name="adminForm"
	id="adminForm"
>
	<div class="form-horizontal">
		<fieldset class="adminform">
			<legend><?php echo JText::_('Download en importeer uit SOL Inschrijfgegevens'); ?></legend>
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group">
						<div class="control-label"> </div>
						<div class="controls"><input class="button" type="submit" value="<?php echo JText::_('Download en importeer Inschrijvingen'); ?>" /></div>
					</div>
				</div>
			</div>
		</fieldset>
	</div>
	
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
	<div class="form-horizontal">
		<fieldset class="adminform">
			<legend><?php echo JText::_('Download en importeer uit SOL Deelnemersgegevens'); ?></legend>
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group">
						<div class="control-label"> </div>
						<div class="controls"><input class="button" type="submit" value="<?php echo JText::_('Download en importeer Deelnemers'); ?>" /></div>
					</div>
				</div>
			</div>
		</fieldset>
	</div>
	
	<div>
		<input type="hidden" name="task" value="import.downloadDeelnemers" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

</form>

<form	action="<?php echo JRoute::_('index.php?option=com_kampinfo&view=import'); ?>"
		enctype="multipart/form-data" 
		method="post"
		name="adminForm"
		id="adminForm"
>
	<div class="form-horizontal">
		<fieldset class="adminform">
			<legend><?php echo JText::_('Importeer Inschrijvingen'); ?></legend>
			<div class="row-fluid">
				<div class="span6">
					<?php foreach($this->form->getFieldset("importInschrijvingen") as $field): ?>
						<div class="control-group">
							<div class="control-label"><?php echo $field->label; ?></div>
							<div class="controls"><?php echo $field->input; ?></div>
						</div>
					<?php endforeach; ?>

					<div class="control-group">
						<div class="control-label"> </div>
						<div class="controls"><input class="button" type="submit" value="<?php echo JText::_('Upload'); ?>" /></div>
					</div>
				</div>
			</div>
		</fieldset>
	</div>
	
	<div>
		<input type="hidden" name="task" value="import.importInschrijvingen" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

</form>

<form action="<?php echo JRoute::_('index.php?option=com_kampinfo&view=import'); ?>"
	enctype="multipart/form-data" 
	method="post"
	name="adminForm"
	id="adminForm"
>
	<div class="form-horizontal">
		<fieldset class="adminform">
			<legend><?php echo JText::_('Importeer Deelnemers'); ?></legend>
			<div class="row-fluid">
				<div class="span6">
					<?php foreach($this->form->getFieldset("importDeelnemers") as $field): ?>
						<div class="control-group">
							<div class="control-label"><?php echo $field->label; ?></div>
							<div class="controls"><?php echo $field->input; ?></div>
						</div>
					<?php endforeach; ?>
					<div class="control-group">
						<div class="control-label"> </div>
						<div class="controls"><input class="button" type="submit" value="<?php echo JText::_('Upload'); ?>" /></div>
					</div>
				</div>
			</div>
		</fieldset>
	</div>
	
	<div>
		<input type="hidden" name="task" value="import.importDeelnemers" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

</form>
</div>
