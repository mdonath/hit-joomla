<?php defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtmlBootstrap::tooltip();
?>
<form	action="<?php echo JRoute::_('index.php?option=com_kampinfo&controller=downloads&view=downloads'); ?>"
		method="post"
		name="adminForm"
		id="adminForm"
>
	<div id="j-sidebar-container" class="col-md-2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="col-md-10">
		<?php echo $this->loadTemplate('list'); ?>
	</div>
</form>