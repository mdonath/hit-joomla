<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtmlBootstrap::tooltip();
?>
<form	action="<?php echo JRoute::_('index.php?option=com_kampinfo&controller=hitcamps&view=hitcamps'); ?>"
		method="post"
		name="adminForm"
		id="adminForm"
>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php echo $this->loadTemplate('list'); ?>
	</div>
</form>