<?php

namespace HITScoutingNL\Component\KampInfo\Site\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Database\ParameterType;

use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;

/**
 * KampInfo Overzichtplaats Model
 */
class OverzichtplaatsModel extends AbstractKampInfoModel {


    public function getPlaats() {
        $input = Factory::getApplication()->input;
        $hitsiteId = $input->getString('hitsite_id', '');

        $plaats = $this->getHitPlaats($hitsiteId);

        $iconenLijst = $this->getIconenLijst();
        $plaats->kampen = $this->getHitKampen($plaats->id, $iconenLijst);

        return $plaats;
    }

}
