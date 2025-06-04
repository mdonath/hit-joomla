<?php

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Language\Text;
?>

<?php if (empty($this->items)) : ?>

    <div class="alert alert-info">
        <span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?= Text::_('INFO') ?></span>
        <?= Text::_('JGLOBAL_NO_MATCHING_RESULTS') ?>
    </div>

<?php else : ?>

    <table class="table table-striped">
        <?= $this->loadTemplate('head') ?>
        <?= $this->loadTemplate('body') ?>
    </table>
    <?= $this->pagination->getListFooter() ?>

<?php endif; ?>
