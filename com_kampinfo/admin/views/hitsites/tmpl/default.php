<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_kampinfo&controller=hitsites&view=hitsites'); ?>" method="post" name="adminForm" id="adminForm">
	
	<?php echo $this->loadTemplate('searchfilter'); ?>
	<?php echo $this->loadTemplate('list'); ?>
	
</form>