<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.helper');
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
	<ul>
		<li><a target="_blank" href="../index.php?option=com_kampinfo&task=hitcourant.generate">HIT Courant</a></li>
		<li><a target="_blank" href="../index.php?option=com_kampinfo&view=shanti&format=raw">Shanti dump</a></li>
		<li><a target="_blank" href="../index.php?option=com_kampinfo&view=financien&format=raw">Financiën</a></li>
	</ul>
</div>
