<?php

\defined('_JEXEC') or die('Restricted Access');


/** @var HITScoutingNL\Component\KampInfo\Administrator\View\Downloads\HtmlView $this */
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
