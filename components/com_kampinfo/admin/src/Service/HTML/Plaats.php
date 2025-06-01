<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Service\HTML;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;


class Plaats {

    public static function naam($item, $canEdit = false) {
        $naam = $item->naam;
        if ($canEdit) {
            $editUrl = Route::_('index.php?option=com_kampinfo&task=site.edit&id=' . (int)$item->id);
            return "<a href='$editUrl'>$naam</a>";
        }
        return $naam;
    }

    public static function downloadPdf($item, $canEdit = false) {
        if ($canEdit) {
            $editUrl = Route::_('index.php?option=com_kampinfo&view=site&format=pdf&id=' . (int)$item->id);
            return "<a href='$editUrl'>download</a>";
        }
        return 'nvt';
    }

}
