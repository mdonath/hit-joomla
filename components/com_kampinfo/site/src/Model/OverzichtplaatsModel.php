<?php

namespace HITScoutingNL\Component\KampInfo\Site\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Database\ParameterType;

use HITScoutingNL\Library\KampInfo\Helper\KampInfoHelper;

/**
 * KampInfo Overzichtplaats Model
 */
class OverzichtplaatsModel extends AbstractKampInfoModel {


    public function getPlaats() {
        $input = Factory::getApplication()->getInput();
        $hitsiteId = $input->getString('hitsite_id', '');

        $plaats = $this->getHitPlaats($hitsiteId);

        $iconenMap = $this->getIconenMap();
        $plaats->kampen = $this->getHitKampen($plaats->id, $iconenMap);

        return $plaats;
    }

}
