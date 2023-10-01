<?php

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die;

$wa = $this->document->getWebAssetManager();
$wa ->useScript('keepalive')
    ->useScript('form.validate');
?>

<form action="<?php echo Route::_('index.php?option=com_kampinfo&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post"
      name="adminForm"
      id="site-form"
      class="form-validate">

    <?php echo $this->form->renderField('id'); ?>

    <div class="row form-vertical">
        <div class="col-md-6">
            <?php echo $this->form->renderField('naam'); ?>
        </div>
        <div class="col-md-6">
            <?php echo $this->form->renderField('hitproject_id'); ?>
        </div>
    </div>

    <div class="main-card">
        <?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'helpdesk', 'recall' => true, 'breakpoint' => 768]); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'helpdesk', 'Helpdesk'); ?>
        <div class="row">
            <div class="col-lg-6">
                <?php echo $this->form->renderFieldset('helpdesk'); ?>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'financien', 'Financiën'); ?>
        <div class="row">
            <div class="col-lg-12">
                <?php echo $this->form->renderFieldset('financien'); ?>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'hitcourant', 'HIT Courant'); ?>
        <div class="row">
            <div class="col-lg-12">
                <?php echo $this->form->renderFieldset('hitcourant'); ?>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'akkoorden', 'Akkoorden'); ?>
        <div class="row">
            <div class="col-lg-12">
                <?php echo $this->form->renderFieldset('akkoorden'); ?>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo HTMLHelper::_('uitab.endTabSet'); ?>
    </div>

    <input type="hidden" name="task" value="site.edit">
    <?php echo HTMLHelper::_('form.token'); ?>

</form>
