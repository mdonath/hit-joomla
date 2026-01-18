<?php

namespace HITScoutingNL\Component\KampInfo\Site\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Date\Date;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Database\ParameterType;

use HITScoutingNL\Library\KampInfo\Helper\KampInfoHelper;
use HITScoutingNL\Library\KampInfo\Icoon\IcoonUtil;


/**
 * KampInfo Overzicht Model
 */
abstract class AbstractKampInfoModel extends BaseDatabaseModel {

    protected function getHitProject($projectId) {
        $db = $this->getDatabase();

        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__kampinfo_hitproject', 'p'))
            ->where($db->quoteName('p.id') .' = :projectId')
            ->bind(':projectId', $projectId, ParameterType::INTEGER)
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
        $db = $this->getDatabase();

        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__kampinfo_hitsite', 's'))
            ->where([
                $db->quoteName('s.published'). ' = 1',
                $db->quoteName('s.akkoordHitPlaats') .' = 1',
                $db->quoteName('s.hitproject_id') . ' = :projectId',
            ])
            ->bind(':projectId', $projectId, ParameterType::INTEGER)
            ->order($db->quoteName('s.naam'))
        ;

        try {
            $db->setQuery($query);
            return $db->loadObjectList();
        } catch (\Exception $e) {
            throw new GenericDataException($e->getMessage(), 500);
        }
    }

    protected function getHitPlaats($hitsiteId) {
        $db = $this->getDatabase();

        $query = $db->getQuery(true)
            ->select('s.*, p.jaar')
            ->from($db->quoteName('#__kampinfo_hitsite', 's'))
            ->join(
                'LEFT',
                $db->quoteName('#__kampinfo_hitproject', 'p'),
                $db->quoteName('s.hitproject_id') .' = '. $db->quoteName('p.id')
            )
            ->where($db->quoteName('s.id') .' = :hitsiteId')
            ->bind(':hitsiteId', $hitsiteId, ParameterType::INTEGER)
        ;

        try {
            $db->setQuery($query);
            $plaats = $db->loadObjectList();
            return $plaats[0];
        } catch (\Exception $e) {
            throw new GenericDataException($e->getMessage(), 500);
        }
    }

    protected function getHitKampen($hitsiteId, $iconenMap) {
        $db = $this->getDatabase();

        $query = $db->getQuery(true)
            ->select('c.*')
            ->from($db->quoteName('#__kampinfo_hitcamp', 'c'))
            ->where([
                $db->quoteName('c.published') . ' = 1',
                $db->quoteName('c.akkoordHitKamp') . ' = 1',
                $db->quoteName('c.akkoordHitPlaats') . ' = 1',
                $db->quoteName('c.hitsite_id') . ' = :hitsiteId',
            ])
            ->bind(':hitsiteId', $hitsiteId, ParameterType::INTEGER)

            ->join('LEFT',
                $db->quoteName('#__kampinfo_hitsite', 's'),
                $db->quoteName('c.hitsite_id') .' = '. $db->quoteName('s.id')
            )

            ->select([
                $db->quoteName('p.inschrijvingStartdatum', 'startInschrijving'),
                $db->quoteName('p.inschrijvingEinddatum', 'eindInschrijving'),
                $db->quoteName('p.loterijStartdatum', 'startLoterij'),
                $db->quoteName('p.loterijEinddatum', 'eindLoterij'),
            ])
            ->join('LEFT',
                $db->quoteName('#__kampinfo_hitproject', 'p'),
                $db->quoteName('s.hitproject_id') .' = '. $db->quoteName('p.id')
            )
            ->order([
                $db->quoteName('c.minimumLeeftijd'),
                $db->quoteName('c.maximumLeeftijd'),
                $db->quoteName('c.naam'),
            ])
        ;

        $iconenMap = $this->getIconenMap();
        try {
            $db->setQuery($query);
            $kampenInPlaats = $db->loadObjectList();

            if (!empty($iconenMap)) {
                foreach ($kampenInPlaats as $kamp) {
                    $kamp->icoontjes = IcoonUtil::explodeIcoontjes($kamp, $iconenMap);
                }
            }
            return $kampenInPlaats;
        } catch (\Exception $e) {
            throw new GenericDataException($e->getMessage(), 500);
        }
    }

    protected function getIconenMap() {
        return IcoonUtil::getIconenMap($this->getDatabase());
    }

    protected function getLaatstBijgewerktOp($jaar) {
        $soort = 'INSC';

        $db = $this->getDatabase();
        
        $query = $db->getQuery(true)
            -> select("max(CONVERT_TZ(`bijgewerktOp`, @@session.time_zone, '+00:00')) as bijgewerktOp")
            -> from($db->quoteName('#__kampinfo_downloads', 'd'))
            -> where('d.jaar = :jaar')
            -> bind(':jaar', $jaar)
            -> where('d.soort = :soort')
            -> bind(':soort', $soort)
        ;

        try {
            $db->setQuery($query);
            $result = $db->loadResult();
            $result = new Date($result);
            $result->setTimezone(KampInfoHelper::getTimezone());
            return $result;
        } catch (\Exception $e) {
            throw new GenericDataException($e->getMessage(), 500);
        }
    }

}
