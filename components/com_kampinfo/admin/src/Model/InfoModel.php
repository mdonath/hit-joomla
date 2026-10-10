<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\MVC\Model\ListModel;

use HITScoutingNL\Library\KampInfo\Metadata\ManifestTrait;


class InfoModel extends ListModel {

    use ManifestTrait;

    public function getItems() {
        return $this->getManifestData('com_kampinfo');
    }

}
