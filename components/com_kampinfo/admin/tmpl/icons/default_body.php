<?php

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoUrlHelper;

$options = KampInfoHelper::getHitIconSoortOptions();

$params = ComponentHelper::getParams('com_kampinfo');
$iconFolderSmall = $params->get('iconFolderSmall');
$iconFolderLarge = $params->get('iconFolderLarge');
$iconExtension = $params->get('iconExtension');

?>

<tbody>
    <?php foreach($this->items as $i => $item) : ?>
        <tr>
            <?php if ($this->canDo->get('hiticon.edit') || $this->canDo->get('hiticon.delete')) { ?>
                <td>
                    <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                </td>
            <?php } ?>
            <td>
                <?php echo $item->volgorde; ?>
            </td>
            <td>
                <?php echo(KampInfoUrlHelper::imgUrl($iconFolderLarge, $item->bestandsnaam, $iconExtension, $item->tekst, $item->tekst)); ?>
            </td>
            <td>
                <?php if ($this->canDo->get('hiticon.edit')) { ?>
                    <a href="<?php echo Route::_('index.php?option=com_kampinfo&task=icon.edit&id='.(int)$item->id); ?>">
                        <?php echo $item->bestandsnaam; ?>
                    </a>
                <?php } else { ?>
                    <?php echo $item->bestandsnaam; ?>
                <?php } ?>
            </td>
            <td class="hidden-phone">
                <?php echo $item->tekst; ?>
            </td>
            <td class="hidden-phone">
                <?php echo $item->uitleg; ?>
            </td>
            <td>
                <?php echo $options[$item->soort]; ?>
            </td>
            <td class="hidden-phone">
                <?php echo $item->id; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
