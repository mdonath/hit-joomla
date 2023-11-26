<?php

namespace HITScoutingNL\Component\KampInfo\Site\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Database\ParameterType;

use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;

/**
 * KampInfo Overzicht Model
 */
abstract class AbstractKampInfoModel extends BaseDatabaseModel {

    protected function getHitProject($projectId) {
        $db = Factory::getDBO();

        $query = $db->getQuery(true)
            -> select('*')
            -> from($db->quoteName('#__kampinfo_hitproject', 'p'))
            -> where('(p.id = :projectId)')
            -> bind(':projectId', $projectId, ParameterType::INTEGER)
        ;

        try {
            $db->setQuery($query);
            $project = $db->loadObjectList();
            return $project[0];
        } catch (\Exception $e) {
            throw new GenericDataException($e->getMessage(), 500);
        }
    }

    protected function getHitPlaatsen($projectId) {
        $db = Factory::getDBO();

        $query = $db->getQuery(true)
            -> select('*')
            -> from($db->quoteName('#__kampinfo_hitsite', 's'))
            -> where('s.hitproject_id = :projectId')
            -> bind(':projectId', $projectId, ParameterType::INTEGER)
            -> where('s.published = 1')
            -> where('s.akkoordHitPlaats = 1')
            -> order('s.naam')
        ;

        try {
            $db->setQuery($query);
            return $db->loadObjectList();
        } catch (\Exception $e) {
            throw new GenericDataException($e->getMessage(), 500);
        }
    }

    protected function getHitPlaats($hitsiteId) {
        $db = Factory::getDBO();

        $query = $db->getQuery(true)
            -> select('s.*, p.jaar')
            -> from($db->quoteName('#__kampinfo_hitsite', 's'))
            -> join(
                'LEFT',
                $db->quoteName('#__kampinfo_hitproject', 'p'),
                $db->quoteName('s.hitproject_id') .' = '. $db->quoteName('p.id')
            )
            -> where('s.id = :hitsiteId')
            -> bind(':hitsiteId', $hitsiteId)
        ;

        try {
            $db->setQuery($query);
            $plaats = $db->loadObjectList();
            return $plaats[0];
        } catch (\Exception $e) {
            throw new GenericDataException($e->getMessage(), 500);
        }
    }

    protected function getHitKampen($hitsiteId, $iconenLijst) {
        $db = Factory::getDBO();

        $query = $db->getQuery(true)
            -> select('*')
            -> from($db->quoteName('#__kampinfo_hitcamp', 'c'))
            -> where('c.hitsite_id = :hitsiteId')
            -> bind(':hitsiteId', $hitsiteId, ParameterType::INTEGER)
            -> where('c.published = 1')
            -> where('c.akkoordHitKamp = 1')
            -> where('c.akkoordHitPlaats = 1')
            -> order('c.minimumLeeftijd, c.maximumLeeftijd, c.naam')
        ;

        try {
            $db->setQuery($query);
            $kampenInPlaats = $db->loadObjectList();

            foreach ($kampenInPlaats as $kamp) {
                $kamp->icoontjes = $this->explodeIcoontjes($kamp, $iconenLijst);
            }
            return $kampenInPlaats;
        } catch (\Exception $e) {
            throw new GenericDataException($e->getMessage(), 500);
        }
    }

    protected function getIconenLijst() {
        $db = Factory::getDBO();

        $query = $db->getQuery(true)
            -> select('i.bestandsnaam as naam, i.tekst, i.volgorde, i.soort')
            -> from($db->quoteName('#__kampinfo_hiticon', 'i'))
        ;

        try {
            $db->setQuery($query);
            $icons = $db->loadObjectList();

            $result = [];
            foreach ($icons as $icon) {
                $i = new \stdClass();
                $i->naam = $icon->naam;
                $i->tekst = $icon->tekst;
                $i->volgorde = $icon->volgorde;
                $i->soort = $icon->soort;
                $result[$icon->naam] = $i;
            }

            return $result;
        } catch (\Exception $e) {
            throw new GenericDataException($e->getMessage(), 500);
        }
    }

    protected function explodeIcoontjes($kamp, $iconenLijst) {
        if (empty($iconenLijst)) {
            $iconenLijst = $this->getIconenLijst();
        }

        $nieuweIcoontjes = [];

        $aantalNachten = KampInfoHelper::aantalOvernachtingen($kamp);
        $overnachtingKey = "aantalnacht".$aantalNachten;
        $nieuweIcoontjes[] = $iconenLijst[$overnachtingKey];
        if (!empty($kamp->icoontjes)) {
            $icoontjes = explode(',', $kamp->icoontjes);
            foreach ($icoontjes as $icoon) {
                if (array_key_exists($icoon, $iconenLijst)) {
                    $nieuweIcoontjes[] = $iconenLijst[$icoon];
                }
            }
        }
        return $nieuweIcoontjes;
    }

    protected function getLaatstBijgewerktOp($jaar) {
        $soort = 'INSC';

        $db = Factory::getDBO();
        
        $query = $db->getQuery(true)
            -> select('max(bijgewerktOp) as bijgewerktOp')
            -> from($db->quoteName('#__kampinfo_downloads', 'd'))
            -> where('d.jaar = :jaar')
            -> bind(':jaar', $jaar)
            -> where('d.soort = :soort')
            -> bind(':soort', $soort)
        ;

        try {
            $db->setQuery($query);
            return $db->loadResult();
        } catch (\Exception $e) {
            throw new GenericDataException($e->getMessage(), 500);
        }
    }

}
