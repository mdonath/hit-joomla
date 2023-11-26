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
        $input = Factory::getApplication()->input;
        $hitcampId = $input->getInt('hitcamp_id', 0);

        if ($hitcampId == 0) {
            throw new GenericDataException('Kamp niet gevonden?!', 404);
        }

        return $this->getHitKampById($hitcampId);
    }

    private function getHitKampById($hitcampId) {
        $db = Factory::getDBO();

        $query = $db->getQuery(true)
            -> select('c.*')
            -> from($db->quoteName('#__kampinfo_hitcamp', 'c'))
            -> where('c.id = :hitcampId')
            -> bind(':hitcampId', $hitcampId)

            -> select($db->quoteName('s.naam', 'plaats'))
            -> select($db->quoteName('s.id', 'hitsite_id'))
            -> join('LEFT', '#__kampinfo_hitsite AS s ON c.hitsite_id = s.id')

            -> select("p.jaar as jaar, p.id as hitproject_id, p.inschrijvingStartdatum as startInschrijving, p.inschrijvingEinddatum as eindInschrijving, IF(c.isouderkind,p.ouderkind,'') AS ouderkind ")
            -> join('LEFT', '#__kampinfo_hitproject AS p ON s.hitproject_id = p.id')
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
            $activiteit->doelgroepen = $this->createDoelgroepen($activiteit->doelgroepen);
            return $activiteit;
        } catch (\Exception $e) {
            throw new GenericDataException($e->getMessage(), 500);
        }
    }

    /**
     * @param $namen - comma separated string
     */
    public function createIcons($namen) {
        $db = Factory::getDBO();

        $values = explode(',', $namen);

        $query = $db->getQuery(true)
            -> select('i.bestandsnaam as naam, i.tekst, i.volgorde')
            -> from($db->quoteName('#__kampinfo_hiticon', 'i'))
            -> where($db->quoteName('i.bestandsnaam') .' IN (' . implode(',', array_map(fn($n) => $db->quote($n), $values)) . ')')
            -> order('i.volgorde')
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
            foreach($options as $option) {
                if ($option->value == $gebied) {
                    $result[] = $option;
                }
            }
        }
        return $result;		
    }

    private function createDoelgroepen($doelgroepen) {
        $doelgroepenLookup = array();
        foreach (KampInfoHelper::getTargetgroupOptions() as $v) {
            $doelgroepenLookup[$v->value] = $v->text;
        }
        $result = '';
        $sep = '';
        foreach (explode(',', $doelgroepen) as $doelgroep) {
            if (!empty($doelgroep)) {
                $result .= $sep . $doelgroepenLookup[$doelgroep];
                $sep = ', ';
            }
        }
        return $result;
    }

}
