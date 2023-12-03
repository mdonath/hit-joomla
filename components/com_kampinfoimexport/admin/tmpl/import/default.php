<?php

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die('Restricted Access');
?>
<div class="main-card">
    <div class="row">
        <div class="col-md-6">
            <form   action="<?php echo Route::_('index.php?option=com_kampinfoimexport&view=import'); ?>"
                    enctype="multipart/form-data" 
                    method="post"
                    name="adminForm"
                    id="adminForm"
                    class="form-validate"
            >
                <fieldset class="options-form">
                    <legend><?php echo Text::_('Importeer totale json-export'); ?></legend>
                    <div>
                        <div class="control-group">
                            <div class="control-label">
                                <label id="import_file-lbl" class="required" for="import_file">
                                    Kies een JSON export
                                </label>
                            </div>
                            <div class="controls">
                                <input id="import_file" class="form-control required" type="file" name="import_file">
                            </div>
                        </div>
                        <input class="btn btn-primary" type="submit" value="<?php echo Text::_('Importeer alles'); ?>" />
                    </div>
                </fieldset>
                <input type="hidden" name="task" value="import.importAlles" />
                <?php echo HTMLHelper::_('form.token'); ?>
            </form>
        </div>

        <div class="col-md-6">
            <form   action="<?php echo Route::_('index.php?option=com_kampinfoimexport&view=import'); ?>"
                    enctype="multipart/form-data" 
                    method="post"
                    name="adminForm"
                    id="adminForm"
                    class="form-validate"
            >
                <fieldset class="options-form">
                    <legend><?php echo Text::_('Importeer enkele plaats'); ?></legend>
                    <div>
                    <div class="control-group">
                            <div class="control-label">
                                <label id="import_file-lbl" class="required" for="import_file">
                                    Kies een JSON export
                                </label>
                            </div>
                            <div class="controls">
                                <input id="import_file" class="form-control required" type="file" name="import_file">
                            </div>
                        </div>

                        <input class="btn btn-primary" type="submit" value="<?php echo Text::_('Importeer plaats'); ?>" />
                    </div>
                </fieldset>
                <input type="hidden" name="task" value="import.importEenPlaats" />
                <?php echo HTMLHelper::_('form.token'); ?>
            </form>
        </div>

    </div>
</div>
