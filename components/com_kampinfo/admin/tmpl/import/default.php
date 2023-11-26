<?php

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
?>

<form action="<?php echo Route::_('index.php?option=com_kampinfo&view=import'); ?>"
      method="post"
      name="adminForm"
      id="adminForm"
      enctype="multipart/form-data"
      class="form-validate form-vertical"
>

    <!-- <div class="main-card"> -->
        <div class="row">
            <div class="col-lg-12">
                <fieldset class="options-form">
                    <legend><?php echo Text::_('Importeer Inschrijvingen'); ?></legend>
                    <?php echo $this->form->renderField('jaar'); ?>
                    <?php echo $this->getForm()->renderField('import_inschrijvingen'); ?>

                    <div class="control-group">
                        <div class="control-label"> </div>
                        <div class="controls">
                            <input class="btn btn-primary" type="submit" value="<?php echo Text::_('Upload'); ?>" />
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        
        <div>
            <input type="hidden" name="task" value="import.importInschrijvingen" />
            <?php echo HTMLHelper::_('form.token'); ?>
        </div>

    <!-- </div> -->
</form>
