<?php

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
// <?= HTMLHelper::date($item->bijgewerktOp, Text::_('COM_KAMPINFO_DATETIME_FORMAT')); ?>
?>

<tbody>
    <?php foreach ($this->items as $i => $item) : ?>
        <tr>
            <td>
                <?= $item->jaar ?>
            </td>
            <td>
                <?= $item->soort ?>
            </td>
            <td>
                <?= $item->bijgewerktOp ?>
            </td>
            <td>
                <?= $item->melding ?>
            </td>
            <td class="hidden-phone">
                <?= $item->id; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
