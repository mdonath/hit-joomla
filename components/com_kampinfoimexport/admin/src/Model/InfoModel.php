<?php

namespace HITScoutingNL\Component\KampInfoImExport\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;

use HITScoutingNL\Library\KampInfo\Metadata\ManifestTrait;


class InfoModel extends ListModel {

    use ManifestTrait;

    public function getItems() {
        return $this->getManifestData('com_kampinfoimexport');
    }

}
