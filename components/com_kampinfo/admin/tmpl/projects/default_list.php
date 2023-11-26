<?php

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Language\Text;
?>

<?php if (empty($this->items)) : ?>

	<div class="alert alert-info">
		<span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
		<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
	</div>

<?php else : ?>

	<table class="table table-striped">
		<?php echo $this->loadTemplate('head'); ?>
		<?php echo $this->loadTemplate('body'); ?>
	</table>
	<?php echo $this->pagination->getListFooter(); ?>

<?php endif; ?>

