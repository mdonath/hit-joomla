<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Service\HTML;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

use HITScoutingNL\Library\KampInfo\Helper\KampInfoUrlHelper;


class Kamp {

    public static function naam($kamp, $canEdit = false) {
        $naam = $kamp->naam . ($kamp->geannuleerd === 1 ? ' (GAAT NIET DOOR)' : '');
        if ($canEdit) {
            $editUrl = Route::_('index.php?option=com_kampinfo&task=camp.edit&id=' . (int)$kamp->id);
            return "<a href='$editUrl'>$naam</a>";
        }
        return $naam;
    }

    public static function icoontjes($kamp, $size = 'small') {
        $result = '';
        if (KampInfoUrlHelper::isVol($kamp)) {
            $result .= HTMLHelper::_('icoon.specifiek', KampInfoUrlHelper::volOfLoterij(), $size, KampInfoUrlHelper::fuzzyIndicatieVol($kamp));
        }
        foreach ($kamp->icoontjes as $icoon) {
            $result .= HTMLHelper::_('icoon.image', $icoon, $size);
        }
        return $result;
    }

    public static function activiteitengebieden($kamp) {
        $result = '';
        foreach ($kamp->activiteitengebieden as $gebied) {
            $result .= HTMLHelper::_('icoon.activiteitengebied', $gebied->value, $gebied->text);
        }
        return $result;
    }

}
