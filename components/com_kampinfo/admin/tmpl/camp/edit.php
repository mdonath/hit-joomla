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
      id="camp-form"
      class="form-validate">

    <?php echo $this->form->renderField('id'); ?>
    <?php echo $this->form->renderField('title'); ?>

    <div class="row form-vertical">
        <div class="col-md-6">
            <?php echo $this->form->renderField('naam'); ?>
        </div>
        <div class="col-md-6">
            <?php echo $this->form->renderField('hitsite_id'); ?>
        </div>
    </div>

    <div class="main-card">
        <?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'algemeen', 'recall' => true, 'breakpoint' => 768]); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'algemeen', 'Algemeen'); ?>
        <div class="row">
            <div class="col-md-6">
                <fieldset id="algemeen" class="options-form">
                    <legend>Algemene info</legend>
                    <div><?php echo $this->form->renderFieldset('algemeen'); ?><div>
                </fieldset>
                <fieldset id="akkoord" class="options-form">
                    <legend>Akkoord</legend>
                    <div><?php echo $this->form->renderFieldset('akkoordkamp'); ?><div>
                    <div><?php echo $this->form->renderFieldset('akkoordplaats'); ?><div>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset id="algemeen" class="options-form">
                    <legend>Informatie voor de Helpdesk</legend>
                    <div><?php echo $this->form->renderFieldset('helpdesk'); ?></div>
                </fieldset>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'hitcourant', 'HIT Courant'); ?>
        <div class="row">
            <div class="col-md-12">
                <?php echo $this->form->renderFieldset('hitcourant'); ?>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'website', 'Website'); ?>
        <div class="row">
            <div class="col-md-8">
                <fieldset id="hitwebsite" class="options-form">
                    <legend>Tekst</legend>
                    <div><?php echo $this->form->renderFieldset('hitwebsite'); ?></div>
                </fieldset>
                <fieldset id="contact" class="options-form">
                    <legend>Contactgegevens</legend>
                    <div><?php echo $this->form->renderFieldset('contact'); ?></div>
                </fieldset>
            </div>
            <div class="col-md-4">
                <fieldset id="fotos" class="options-form">
                    <legend>Foto's</legend>
                    <div><?php echo $this->form->renderFieldset('fotos'); ?></div>
                </fieldset>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'iconen', 'Iconen'); ?>
        <div class="row">
            <div class="col-md-6">
                <fieldset id="iconen" class="options-form">
                    <legend>Iconen</legend>
                    <div><?php echo $this->form->renderFieldset('iconen'); ?></div>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset id="activiteitengebieden" class="options-form">
                    <legend>Activiteitengebieden</legend>
                    <div><?php echo $this->form->renderFieldset('activiteitengebieden'); ?></div>
                </fieldset>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'deelnemer', 'Deelnemer'); ?>
        <div class="row">
            <div class="col-md-6">
                <fieldset id="leeftijd" class="options-form">
                    <legend>Leeftijdsgrenzen deelnemer</legend>
                    <div><?php echo $this->form->renderFieldset('leeftijd'); ?></div>
                </fieldset>
                <fieldset id="leeftijd-ouder" class="options-form">
                    <legend>Leeftijdsgrenzen ouder</legend>
                    <div><?php echo $this->form->renderFieldset('leeftijd-ouder'); ?></div>
                </fieldset>
                
            </div>
            <div class="col-md-6">
                <fieldset id="aantallen" class="options-form">
                    <legend>Aantal deelnemers</legend>
                    <div><?php echo $this->form->renderFieldset('aantallen'); ?></div>
                </fieldset>
                <fieldset id="subgroep" class="options-form">
                    <legend>Subgroep</legend>
                    <div><?php echo $this->form->renderFieldset('subgroep'); ?></div>
                </fieldset>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'doelstelling', 'Doelstelling'); ?>
        <div class="row">
            <div class="col-md-12">
                <?php echo $this->form->renderFieldset('doelstelling'); ?>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'admin', 'Admin'); ?>
        <div class="row">
        </div>
        <div class="row">
            <div class="col-md-6">
                <fieldset id="shanti" class="options-form">
                    <legend>SOL</legend>
                    <div><?php echo $this->form->renderFieldset('shanti'); ?></div>
                </fieldset>
                <fieldset id="inschrijvingen" class="options-form">
                    <legend>Inschrijvingen</legend>
                    <div><?php echo $this->form->renderFieldset('inschrijvingen'); ?></div>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset id="publish" class="options-form">
                    <legend>Publiceren</legend>
                    <div><?php echo $this->form->renderFieldset('publish'); ?></div>
                </fieldset>
            </div>
        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'permissions', Text::_('JCONFIG_PERMISSIONS_LABEL')); ?>
        <div class="row">
                <div class="col-lg-12">
                    <fieldset id="fieldset-rules" class="options-form">
                        <legend><?php echo(Text::_('JCONFIG_PERMISSIONS_LABEL')); ?></legend>
                        <div>
                            <?php echo $this->form->renderFieldset('permissions'); ?>
                        </div>
                    </fieldset>
                </div>
            </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>

        <?php echo HTMLHelper::_('uitab.endTabSet'); ?>
    </div>

    <input type="hidden" name="task" value="camp.edit">
    <?php echo HTMLHelper::_('form.token'); ?>

</form>
