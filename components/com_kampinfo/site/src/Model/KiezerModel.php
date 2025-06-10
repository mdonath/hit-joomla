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
 * KampInfo HIT Kiezer Model
 */
class KiezerModel extends AbstractKampInfoModel {

    public function getProject() {
        $input = Factory::getApplication()->getInput();
        $projectId = $input->getInt('hitproject_id', 0);

        $project = $this->getHitProject($projectId);
        $project->hitPlaatsen = $this->getHitPlaatsen($projectId);

        $iconenMap = $this->getIconenMap();
        $project->gebruikteIconen = array_map(
            fn($value) => $value,
            $iconenMap
        );


        foreach ($project->hitPlaatsen as $plaats) {
            $plaats->kampen = $this->getHitKampenJSON($plaats->id, $iconenMap);
        }

        return $project;
    }

    private function getHitKampenJSON($hitsiteId, $iconenMap) {
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
                $kamp->iconen = IcoonUtil::explodeIcoontjes($kamp, $iconenMap);
                unset($kamp->icoontjes);
            }
            return $kampenInPlaats;
        } catch (\Exception $e) {
            throw new GenericDataException($e->getMessage(), 500);
        }
    }

}
