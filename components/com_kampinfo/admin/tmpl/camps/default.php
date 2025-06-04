<?php

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
?>

<form   action="<?= Route::_('index.php?option=com_kampinfo&controller=camps') ?>"
        method="post"
        name="adminForm"
        id="adminForm"
>
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
                <?= LayoutHelper::render('joomla.searchtools.default', ['view' => $this]) ?>
                <?= $this->loadTemplate('list') ?>
            </div>
        </div>
    </div>

    <input type="hidden" name="task" value="">
    <input type="hidden" name="boxchecked" value="0">
    <?= HTMLHelper::_('form.token') ?>
</form>
