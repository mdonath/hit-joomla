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

$user = $this->getCurrentUser();

$canEdit    = $user->authorise('hiticon.edit', 'com_kampinfo');
$canDelete  = $user->authorise('hiticon.delete', 'com_kampinfo');
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
                <?php echo KampInfoUrlHelper::imgUrl($iconFolderLarge, $item->bestandsnaam, $iconExtension, $item->tekst, $item->tekst); ?>
            </td>
            <td>
                <?php if ($canEdit) : ?>
                    <a href="<?php echo Route::_('index.php?option=com_kampinfo&task=icon.edit&id=' . (int)$item->id); ?>">
                        <?= $item->bestandsnaam ?>
                    </a>
                <?php else : ?>
                    <?= $item->bestandsnaam ?>
                <?php endif; ?>
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
