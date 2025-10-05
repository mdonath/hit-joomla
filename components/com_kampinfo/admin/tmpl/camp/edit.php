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

<form action="<?= Route::_('index.php?option=com_kampinfo&layout=edit&id=' . (int) $this->item->id) ?>"
      method="post"
      name="adminForm"
      id="camp-form"
      class="form-validate">

    <?= $this->form->renderField('id') ?>
    <?= $this->form->renderField('title') ?>

    <div class="row form-vertical">
        <div class="col-md-6">
            <?= $this->form->renderField('naam') ?>
        </div>
        <div class="col-md-6">
            <?= $this->form->renderField('hitsite_id') ?>
        </div>
    </div>

    <div class="main-card">
        <?= HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'algemeen', 'recall' => true, 'breakpoint' => 768]) ?>

        <?= HTMLHelper::_('uitab.addTab', 'myTab', 'algemeen', 'Algemeen') ?>
        <div class="row">
            <div class="col-md-6">
                <fieldset id="algemeen" class="options-form">
                    <legend>Algemene info</legend>
                    <div><?= $this->form->renderFieldset('algemeen') ?><div>
                </fieldset>
                <fieldset id="akkoord" class="options-form">
                    <legend>Akkoord</legend>
                    <div><?= $this->form->renderFieldset('akkoordkamp') ?><div>
                    <div><?= $this->form->renderFieldset('akkoordplaats') ?><div>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset id="algemeen" class="options-form">
                    <legend>Informatie voor de Helpdesk</legend>
                    <div><?= $this->form->renderFieldset('helpdesk') ?></div>
                </fieldset>
            </div>
        </div>
        <?= HTMLHelper::_('uitab.endTab') ?>

        <?= HTMLHelper::_('uitab.addTab', 'myTab', 'hitcourant', 'HIT Courant') ?>
        <div class="row">
            <div class="col-md-12">
                <?= $this->form->renderFieldset('hitcourant') ?>
            </div>
        </div>
        <?= HTMLHelper::_('uitab.endTab') ?>

        <?= HTMLHelper::_('uitab.addTab', 'myTab', 'website', 'Website') ?>
        <div class="row">
            <div class="col-md-8">
                <?= $this->form->renderFieldset('hitwebsite') ?>

                <fieldset id="contact" class="options-form">
                    <legend>Contactgegevens</legend>
                    <div><?= $this->form->renderFieldset('contact') ?></div>
                </fieldset>
            </div>
            <div class="col-md-4">
                <fieldset id="fotos" class="options-form">
                    <legend>Foto's</legend>
                    <div><?= $this->form->renderFieldset('fotos') ?></div>
                </fieldset>
            </div>
        </div>
        <?= HTMLHelper::_('uitab.endTab') ?>

        <?= HTMLHelper::_('uitab.addTab', 'myTab', 'iconen', 'Iconen') ?>
        <div class="row">
            <div class="col-md-6">
                <fieldset id="iconen" class="options-form">
                    <legend>Iconen</legend>
                    <div><?= $this->form->renderFieldset('iconen') ?></div>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset id="activiteitengebieden" class="options-form">
                    <legend>Activiteitengebieden</legend>
                    <div><?= $this->form->renderFieldset('activiteitengebieden') ?></div>
                </fieldset>
            </div>
        </div>
        <?= HTMLHelper::_('uitab.endTab') ?>

        <?= HTMLHelper::_('uitab.addTab', 'myTab', 'deelnemer', 'Deelnemer') ?>
        <div class="row">
            <div class="col-md-6">
                <fieldset id="leeftijd" class="options-form">
                    <legend>Leeftijdsgrenzen deelnemer</legend>
                    <div><?= $this->form->renderFieldset('leeftijd') ?></div>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset id="leeftijdOuder" class="options-form">
                    <legend>Leeftijdsgrenzen ouder</legend>
                    <div><?= $this->form->renderFieldset('leeftijdOuder') ?></div>
                </fieldset>
            </div>
        </div>
        <?= HTMLHelper::_('uitab.endTab') ?>

        <?= HTMLHelper::_('uitab.addTab', 'myTab', 'aantallen', 'Aantallen') ?>
        <div class="row">
            <div class="col-md-6">
                <fieldset id="aantallen" class="options-form">
                    <legend>Aantal deelnemers</legend>
                    <div><?= $this->form->renderFieldset('aantallen') ?></div>
                </fieldset>
            </div>
            <div class="col-md-6">
                <fieldset id="subgroep" class="options-form">
                    <legend>Subgroep</legend>
                    <div><?= $this->form->renderFieldset('subgroep') ?></div>
                </fieldset>
            </div>
        </div>
        <?= HTMLHelper::_('uitab.endTab') ?>

        <?= HTMLHelper::_('uitab.addTab', 'myTab', 'doelstelling', 'Doelstelling') ?>
        <div class="row">
            <div class="col-md-12">
                <?= $this->form->renderFieldset('doelstelling') ?>
            </div>
        </div>
        <?= HTMLHelper::_('uitab.endTab') ?>

        <?php if ($user->authorise('core.admin', 'com_kampinfo')) { ?>
            <?= HTMLHelper::_('uitab.addTab', 'myTab', 'admin', 'Admin') ?>
            <div class="row">
                <div class="col-md-6">
                    <fieldset id="shanti" class="options-form">
                        <legend>SOL</legend>
                        <div><?= $this->form->renderFieldset('shanti') ?></div>
                    </fieldset>
                    <fieldset id="inschrijvingen" class="options-form">
                        <legend>Inschrijvingen</legend>
                        <div><?= $this->form->renderFieldset('inschrijvingen') ?></div>
                    </fieldset>
                </div>
                <div class="col-md-6">
                    <fieldset id="publish" class="options-form">
                        <legend>Publiceren</legend>
                        <div><?= $this->form->renderFieldset('publish') ?></div>
                    </fieldset>
                </div>
            </div>
            <?= HTMLHelper::_('uitab.endTab') ?>

            <?= HTMLHelper::_('uitab.addTab', 'myTab', 'permissions', Text::_('JCONFIG_PERMISSIONS_LABEL')) ?>
            <div class="row">
                    <div class="col-lg-12">
                        <fieldset id="fieldset-rules" class="options-form">
                            <legend><?= Text::_('JCONFIG_PERMISSIONS_LABEL') ?></legend>
                            <div>
                                <?= $this->form->renderFieldset('permissions') ?>
                            </div>
                        </fieldset>
                    </div>
                </div>
            <?= HTMLHelper::_('uitab.endTab') ?>
        <?php } ?>

        <?= HTMLHelper::_('uitab.endTabSet') ?>
    </div>

    <input type="hidden" name="task" value="camp.edit">
    <?= HTMLHelper::_('form.token') ?>

</form>
