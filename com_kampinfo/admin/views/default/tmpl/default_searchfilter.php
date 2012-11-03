<?php
// no direct access
defined('_JEXEC') or die;
?>

<fieldset id="filter-bar">
	<div class="filter-search fltlft">
		<?php echo $this->loadTemplate('search');?>
	</div>

	<div class="filter-select fltrt">
		<?php echo $this->loadTemplate('filter');?>
	</div>
</fieldset>
<div class="clr"> </div>
