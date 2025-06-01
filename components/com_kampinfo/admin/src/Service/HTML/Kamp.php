<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Service\HTML;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;


class Kamp {

    public static function naam($item, $canEdit = false) {
        $naam = $item->naam . ($item->geannuleerd === 1 ? ' (GAAT NIET DOOR)' : '');
        if ($canEdit) {
            $editUrl = Route::_('index.php?option=com_kampinfo&task=camp.edit&id='.(int)$item->id);
            return "<a href='$editUrl'>$naam</a>";
        }
        return $naam;
    }

}
