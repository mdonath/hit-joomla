<?php

namespace HITScoutingNL\Component\KampInfo\Site\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Database\ParameterType;

use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;

/**
 * KampInfo HIT Kiezer Model
 */
class KiezerModel extends AbstractKampInfoModel {

    public function getProject() {
        $input = Factory::getApplication()->input;
        $projectId = $input->getInt('hitproject_id', 0);

        $project = $this->getHitProject($projectId);
        $project->hitPlaatsen = $this->getHitPlaatsen($projectId);
        $project->gebruikteIconen = $this->getIconenLijstJSON();

        $iconenLookup = [];
        foreach ($project->gebruikteIconen as $icon) {
            $iconenLookup[$icon->bestandsnaam] = $icon;
        }

        foreach ($project->hitPlaatsen as $plaats) {
            $plaats->kampen = $this->getHitKampenJSON($plaats->id, $iconenLookup);
        }

        return $project;
    }

    private function getIconenLijstJSON() {
        $db = Factory::getDBO();

        $query = $db->getQuery(true)
            -> select('i.volgorde, i.bestandsnaam, i.tekst')
            -> from('#__kampinfo_hiticon i')
        ;

        try {
            $db->setQuery($query);
            return $db->loadObjectList();
        } catch (\Exception $e) {
            throw new GenericDataException($e->getMessage(), 500);
        }
    }

    private function getHitKampenJSON($hitsiteId, $iconenLookup) {
        $db = Factory::getDBO();

        $query = $db->getQuery(true)
            -> select([
                'c.naam',
                'c.shantiFormuliernummer',
                'c.minimumLeeftijd',
                'c.maximumLeeftijd',
                'c.deelnamekosten',
                'c.minimumAantalDeelnemers',
                'c.maximumAantalDeelnemers',
                'c.aantalDeelnemers',
                'c.gereserveerd',
                'c.subgroepsamenstellingMinimum',
                'c.aantalSubgroepen',
                'c.maximumAantalSubgroepjes',
                'c.icoontjes',
                'c.margeAantalDagenTeJong',
                'c.margeAantalDagenTeOud',
                'c.startDatumTijd',
                'c.eindDatumTijd',
                'c.isouderkind',
                'c.minimumLeeftijdOuder',
                'c.maximumLeeftijdOuder'
            ])
            -> from('#__kampinfo_hitcamp c')
            -> where('c.hitsite_id = :hitsiteId')
            -> bind(':hitsiteId', $hitsiteId)
            -> where('c.published = 1')
            -> where('c.akkoordHitKamp = 1')
            -> where('c.akkoordHitPlaats = 1')
            -> order('c.naam')
        ;

        try {
            $db->setQuery($query);
            $kampenInPlaats = $db->loadObjectList();

            foreach ($kampenInPlaats as $kamp) {
                $nieuweIcoontjes = [];
                if (!empty($kamp->icoontjes)) {
                    $aantalNachten = KampInfoHelper::aantalOvernachtingen($kamp);
                    $kamp->icoontjes = "aantalnacht{$aantalNachten},". $kamp->icoontjes;
                    $icoontjes = explode(',', $kamp->icoontjes);
                    foreach ($icoontjes as $icoon) {
                        $lookedUp = $iconenLookup[$icoon];
                        if ($lookedUp != null) {
                            $nieuweIcoontjes[] = $lookedUp;
                        }
                    }
                }
                $kamp->iconen = $nieuweIcoontjes;
                unset($kamp->icoontjes);
            }
            return $kampenInPlaats;
        } catch (\Exception $e) {
            throw new GenericDataException($e->getMessage(), 500);
        }
    }

}
