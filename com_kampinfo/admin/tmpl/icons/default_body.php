<?php

defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoUrlHelper;

$options = KampInfoHelper::getHitIconSoortOptions();

$params = ComponentHelper::getParams('com_kampinfo');
$iconFolderSmall = $params->get('iconFolderSmall');
$iconExtension = $params->get('iconExtension');

?>

<tbody>
    <?php foreach($this->items as $i => $item) : ?>
        <tr>
            <td>
                <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
            </td>
            <td>
                <?php echo $item->volgorde; ?>
            </td>
            <td>
                <?php echo(KampInfoUrlHelper::imgUrl($iconFolderSmall, $item->bestandsnaam, $iconExtension, $item->bestandsnaam, $item->bestandsnaam)); ?>
            </td>
            <td>
                <a href="<?php echo Route::_('index.php?option=com_kampinfo&task=icon.edit&id='.(int)$item->id); ?>">
                    <?php echo $item->bestandsnaam; ?>
                </a>
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
