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
        $input = Factory::getApplication()->getInput();
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
        $db = $this->getDatabase();

        $query = $db->getQuery(true)
            ->select([
                $db->quoteName('i.volgorde'),
                $db->quoteName('i.bestandsnaam'),
                $db->quoteName('i.tekst'),
            ])
            ->from($db->quoteName('#__kampinfo_hiticon', 'i'))
        ;

        try {
            $db->setQuery($query);
            return $db->loadObjectList();
        } catch (\Exception $e) {
            throw new GenericDataException($e->getMessage(), 500);
        }
    }

    private function getHitKampenJSON($hitsiteId, $iconenLookup) {
        $db = $this->getDatabase();

        $query = $db->getQuery(true)
            ->select([
                $db->quoteName('c.naam'),
                $db->quoteName('c.shantiFormuliernummer'),
                $db->quoteName('c.minimumLeeftijd'),
                $db->quoteName('c.maximumLeeftijd'),
                $db->quoteName('c.deelnamekosten'),
                $db->quoteName('c.minimumAantalDeelnemers'),
                $db->quoteName('c.maximumAantalDeelnemers'),
                $db->quoteName('c.aantalDeelnemers'),
                $db->quoteName('c.gereserveerd'),
                $db->quoteName('c.subgroepsamenstellingMinimum'),
                $db->quoteName('c.aantalSubgroepen'),
                $db->quoteName('c.maximumAantalSubgroepjes'),
                $db->quoteName('c.icoontjes'),
                $db->quoteName('c.margeAantalDagenTeJong'),
                $db->quoteName('c.margeAantalDagenTeOud'),
                $db->quoteName('c.startDatumTijd'),
                $db->quoteName('c.eindDatumTijd'),
                $db->quoteName('c.isouderkind'),
                $db->quoteName('c.minimumLeeftijdOuder'),
                $db->quoteName('c.maximumLeeftijdOuder'),
            ])
            ->from($db->quoteName('#__kampinfo_hitcamp', 'c'))
            ->where($db->quoteName('c.hitsite_id'). ' = :hitsiteId')
            ->bind(':hitsiteId', $hitsiteId)
            ->where($db->quoteName('c.published') . ' = 1')
            ->where($db->quoteName('c.akkoordHitKamp') . ' = 1')
            ->where($db->quoteName('c.akkoordHitPlaats') . ' = 1')
            ->where($db->quoteName('c.geannuleerd') . ' <> 1')
            ->order($db->quoteName('c.naam'))
        ;

        try {
            $db->setQuery($query);
            $kampenInPlaats = $db->loadObjectList();

            foreach ($kampenInPlaats as $kamp) {
                $nieuweIcoontjes = [];
                if (!empty($kamp->icoontjes)) {
                    $automagischToegevoegd = '';
                    $aantalNachten = KampInfoHelper::aantalOvernachtingen($kamp);
                    if ($aantalNachten > 0) {
                        $automagischToegevoegd .= "aantalnacht{$aantalNachten},";
                    }
                    if ($kamp->isouderkind == 1) {
                        $automagischToegevoegd .= 'ouderkind,';
                    }
                    $kamp->icoontjes = $automagischToegevoegd . $kamp->icoontjes;
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
