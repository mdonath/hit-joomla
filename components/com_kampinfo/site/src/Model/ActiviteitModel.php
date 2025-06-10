<?php

namespace HITScoutingNL\Component\KampInfo\Site\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Database\ParameterType;

use HITScoutingNL\Library\KampInfo\Helper\KampInfoHelper;
use HITScoutingNL\Library\KampInfo\Icoon\IcoonUtil;


/**
 * KampInfo Activiteit Model
 */
class ActiviteitModel extends AbstractKampInfoModel {

    public function getActiviteit() {
        $input = Factory::getApplication()->getInput();
        $hitcampId = $input->getInt('hitcamp_id', 0);

        if ($hitcampId == 0) {
            throw new GenericDataException('Kamp niet gevonden?!', 404);
        }

        return $this->getHitKampById($hitcampId);
    }

    private function getHitKampById($hitcampId) {
        $db = $this->getDatabase();

        $query = $db->getQuery(true)
            ->select('c.*')
            ->from($db->quoteName('#__kampinfo_hitcamp', 'c'))
            ->where($db->quoteName('c.id') . ' = :hitcampId')
            ->bind(':hitcampId', $hitcampId)

            ->select([
                $db->quoteName('s.naam', 'plaats'),
                $db->quoteName('s.id', 'hitsite_id')
            ])
            ->join('LEFT',
                $db->quoteName('#__kampinfo_hitsite', 's'),
                $db->quoteName('c.hitsite_id') .' = '. $db->quoteName('s.id')
            )

            ->select([
                $db->quoteName('p.jaar', 'jaar'),
                $db->quoteName('p.id', 'hitproject_id'),
                $db->quoteName('p.inschrijvingStartdatum', 'startInschrijving'),
                $db->quoteName('p.inschrijvingEinddatum', 'eindInschrijving'),
                'IF('.
                    $db->quoteName('c.isouderkind') .
                    ',' .
                    $db->quoteName('p.ouderkind') .
                    ',' .
                    $db->quote('') .
                ') AS ouderkind'
            ])
            ->join('LEFT',
                $db->quoteName('#__kampinfo_hitproject', 'p'),
                $db->quoteName('s.hitproject_id') .' = '. $db->quoteName('p.id')
            )
        ;

        try {
            $db->setQuery($query);
            $activiteiten = $db->loadObjectList();

            if (count($activiteiten) != 1) {
                throw new GenericDataException("0 of meer dan 1 gevonden met id $hitcampId in jaar $jaar.", 500);
            }

            $activiteit = $activiteiten[0];
    
            $iconenMap = $this->getIconenMap();
            $activiteit->icoontjes = IcoonUtil::explodeIcoontjes($activiteit, $iconenMap);

            $activiteit->activiteitengebieden = $this->createActiviteitengebieden($activiteit->activiteitengebieden);
            return $activiteit;
        } catch (\Exception $e) {
            throw new GenericDataException($e->getMessage(), 500);
        }
    }

    private function createActiviteitengebieden($activiteitengebieden) {
        $activiteitengebieden = explode(',', $activiteitengebieden);
        $options = KampInfoHelper::getActivityAreaOptions();
        $result = array();
        foreach ($activiteitengebieden as $gebied) {
            foreach ($options as $option) {
                if ($option->value == $gebied) {
                    $result[] = $option;
                }
            }
        }
        return $result;
    }

}
