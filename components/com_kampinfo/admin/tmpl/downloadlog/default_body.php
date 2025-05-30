<?php

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
?>

<tbody>
    <?php foreach($this->items as $i => $item) : ?>
        <tr>
            <td>
                <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
            </td>
            <td>
                <?php echo Text::_($item->jaar); ?>
            </td>
            <td>
                <?php echo Text::_($item->soort); ?>
            </td>
            <td>
                <?php echo Text::_($item->bijgewerktOp); ?>
            </td>
            <td>
                <?php echo Text::_($item->melding); ?>
            </td>
            <td>
                <?php echo $item->id; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
