<?php

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$wa = $this->document->getWebAssetManager();
$wa ->useScript('keepalive')
    ->useScript('form.validate');
?>

<form action="<?php echo Route::_('index.php?option=com_kampinfo&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post"
      name="adminForm"
      id="project-form"
      class="form-validate">

    <div class="row">
        <div class="col-lg-6">
            <?php echo $this->form->renderFieldset('algemeen'); ?>
        </div>
    </div>

    <div class="main-card">
        <?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'inschrijving', 'recall' => true, 'breakpoint' => 768]); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'inschrijving', 'Inschrijving'); ?>
        <div class="row">
            <div class="col-lg-6">
                <?php echo $this->form->renderFieldset('inschrijving'); ?>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'pasen', 'Pasen'); ?>
        <div class="row">
            <div class="col-lg-6">
                <?php echo $this->form->renderFieldset('pasen'); ?>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'teksten', 'Teksten'); ?>
        <div class="row">
            <div class="col-lg-12">
                <?php echo $this->form->renderFieldset('teksten'); ?>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo HTMLHelper::_('uitab.endTabSet'); ?>
    </div>

    <input type="hidden" name="task" value="project.edit">
    <?php echo HTMLHelper::_('form.token'); ?>

</form>
