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
      id="icon-form"
      class="form-validate">

    <div class="row">
        <div class="col-lg-6">
            <?php echo $this->form->renderFieldset('hiticon'); ?>
        </div>
    </div>

    <input type="hidden" name="task" value="icon.edit">
    <?php echo HTMLHelper::_('form.token'); ?>

</form>
