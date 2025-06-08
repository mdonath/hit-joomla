<?php

namespace HITScoutingNL\Component\KampInfo\Site\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Database\ParameterType;

use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;

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
    
            $aantalNachten = KampInfoHelper::aantalOvernachtingen($activiteit);
            $activiteit->icoontjes = "aantalnacht{$aantalNachten},". $activiteit->icoontjes;
            $activiteit->icoontjes =  $this->createIcons($activiteit->icoontjes);
            $activiteit->activiteitengebieden = $this->createActiviteitengebieden($activiteit->activiteitengebieden);
            return $activiteit;
        } catch (\Exception $e) {
            throw new GenericDataException($e->getMessage(), 500);
        }
    }

    /**
     * @param $namen - comma separated string
     */
    public function createIcons($namen) {
        $db = $this->getDatabase();

        $values = implode(
            ',',
            array_map(
                fn($n) => $db->quote($n),
                explode(',', $namen)
            )
        );

        $query = $db->getQuery(true)
            ->select([
                $db->quoteName('i.bestandsnaam'),
                $db->quoteName('i.tekst'),
                $db->quoteName('i.volgorde'),
            ])
            ->from($db->quoteName('#__kampinfo_hiticon', 'i'))
            ->where($db->quoteName('i.bestandsnaam') .' IN (' . $values . ')')
            ->order('i.volgorde')
        ;
        
        try {
            $db->setQuery($query);
            $icons = $db->loadObjectList();
            return $icons;
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
