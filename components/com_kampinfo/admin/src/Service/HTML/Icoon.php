<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Service\HTML;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoUrlHelper;


class Icoon {

    public static function naam($item, $canEdit = false) {
        $naam = $item->bestandsnaam;
        if ($canEdit) {
            $editUrl = Route::_('index.php?option=com_kampinfo&task=icon.edit&id=' . (int)$item->id);
            return "<a href='$editUrl'>$naam</a>";
        }
        return $naam;
    }

    public static function image($item, $size = 'large') {
        $result = static::specifiek($item->bestandsnaam, $size, $item->tekst);
        return $result;
    }

    public static function specifiek($naam, $size = 'large', $titel = '') {
        $params = ComponentHelper::getParams('com_kampinfo');
        $iconExtension = $params->get('iconExtension');

        if ($size === 'large') {
            $folder = $params->get('iconFolderLarge');
        } else if ($size === 'small') {
            $folder = $params->get('iconFolderSmall');
        }

        return HTMLHelper::_('image', $folder .'/'. $naam . $iconExtension, $titel, ['title' => $titel]);
    }

    public static function activiteitengebied($naam, $titel) {
        $params = ComponentHelper::getParams('com_kampinfo');
        $folder = $params->get('activiteitengebiedenFolder');
        $ext = $params->get('activiteitengebiedenExtension');

        return HTMLHelper::_('image', $folder .'/'. $naam . $ext, $titel, ['title' => $titel]);
    }

}
