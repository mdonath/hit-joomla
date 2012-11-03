<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_kampinfo&controller=hitprojects&view=hitprojects'); ?>" method="post" name="adminForm" id="adminForm">

	<?php echo $this->loadTemplate('list'); ?>

</form>