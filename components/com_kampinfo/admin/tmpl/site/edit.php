<?php

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$wa = $this->document->getWebAssetManager();
$wa ->useScript('keepalive')
    ->useScript('form.validate');

$user = $this->getCurrentUser();
?>

<form action="<?php echo Route::_('index.php?option=com_kampinfo&layout=edit&id=' . (int) $this->item->id); ?>"
      method="post"
      name="adminForm"
      id="site-form"
      class="form-validate">

      <?php echo $this->form->renderField('id'); ?>
      <?php echo $this->form->renderField('title'); ?>

    <div class="row form-vertical">
        <div class="col-md-6">
            <?php echo $this->form->renderField('naam'); ?>
        </div>
        <div class="col-md-6">
            <?php echo $this->form->renderField('hitproject_id'); ?>
        </div>
    </div>

    <div class="main-card">
        <?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'algemeen', 'recall' => true, 'breakpoint' => 768]); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'algemeen', 'Algemeen'); ?>
        <div class="row">
            <div class="col-lg-8">
                <?php echo $this->form->renderFieldset('hitcourant'); ?>
            </div>
            <div class="col-lg-4">
                <?php echo $this->form->renderFieldset('akkoorden'); ?>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'socialmedia', 'Social Media'); ?>
        <div class="row">
            <div class="col-lg-6">
                <div><?php echo $this->form->renderFieldset('socialmedia'); ?></div>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'helpdesk', 'Helpdesk'); ?>
        <div class="row">
            <div class="col-lg-6">
                <div><?php echo $this->form->renderFieldset('helpdesk'); ?></div>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php if ($user->authorise('core.admin', 'com_kampinfo')) { ?>
            <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'financien', 'Financiën'); ?>
            <div class="row">
                <div class="col-lg-6">
                    <?php echo $this->form->renderFieldset('financien'); ?>
                </div>
            </div>
            <?php echo HTMLHelper::_('uitab.endTab'); ?>

            <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'permissions', Text::_('JCONFIG_PERMISSIONS_LABEL')); ?>
            <div class="row">
                <div class="col-lg-12">
                    <fieldset id="fieldset-rules" class="options-form">
                        <legend><?php echo Text::_('JCONFIG_PERMISSIONS_LABEL'); ?></legend>
                        <div>
                            <?php echo $this->form->renderFieldset('permissions'); ?>
                        </div>
                    </fieldset>
                </div>
            </div>
            <?php echo HTMLHelper::_('uitab.endTab'); ?>
        <?php } ?>

        <?php echo HTMLHelper::_('uitab.endTabSet'); ?>
    </div>

    <input type="hidden" name="task" value="site.edit">
    <?php echo HTMLHelper::_('form.token'); ?>

</form>
