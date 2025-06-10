<?php
namespace HITScoutingNL\Library\KampInfo\Icoon;

\defined('_JEXEC') or die;

use Joomla\Database\DatabaseInterface;

use HITScoutingNL\Library\KampInfo\Helper\KampInfoHelper;


abstract class IcoonUtil {

    public static function getIconenMap(DatabaseInterface $db) {
        $query = $db->getQuery(true)
            ->select([
                $db->quoteName('i.bestandsnaam'),
                $db->quoteName('i.tekst'),
                $db->quoteName('i.volgorde'),
                $db->quoteName('i.soort'),
            ])
            ->from($db->quoteName('#__kampinfo_hiticon', 'i'))
            ->order($db->quoteName('i.bestandsnaam'))
        ;

        try {
            $db->setQuery($query);
            $icons = $db->loadObjectList();
            
            $result = [];
            foreach ($icons as $icon) {
                $result[$icon->bestandsnaam] = (object)
                [
                    'bestandsnaam' => $icon->bestandsnaam,
                    'tekst' => $icon->tekst,
                    'volgorde' => $icon->volgorde,
                    'soort' => $icon->soort
                ];
            }
            return $result;
        } catch (\RuntimeException $e) {
            throw new GenericDataException($e->getMessage(), 500);
        }
    }

    public static function explodeIcoontjes($kamp, $iconenMap) {
        $result = [];

        $aantalNachten = KampInfoHelper::aantalOvernachtingen($kamp);
        if ($aantalNachten > 0) {
            $overnachtingKey = "aantalnacht$aantalNachten";
            $result[] = $iconenMap[$overnachtingKey];
        }
        if ($kamp->isouderkind == 1) {
            $result[] = $iconenMap['ouderkind'];
        }
        if (!empty($kamp->icoontjes)) {
            $icoontjes = explode(',', $kamp->icoontjes);
            foreach ($icoontjes as $icoon) {
                if (array_key_exists($icoon, $iconenMap)) {
                    $result[] = $iconenMap[$icoon];
                }
            }
        }

        // sorteer eerst op volgorde en dan op bestandsnaam
        usort($result, fn($a, $b) => [$a->volgorde, $a->bestandsnaam] <=> [$b->volgorde, $b->bestandsnaam]);
        return $result;
    }

}
