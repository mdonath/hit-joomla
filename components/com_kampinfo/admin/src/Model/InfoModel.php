<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\MVC\Model\ItemModel;

use HITScoutingNL\Library\KampInfo\Metadata\ManifestTrait;


class InfoModel extends ItemModel {

    use ManifestTrait;

    public function getItem($pk = null) {
        return $this->getManifestData('com_kampinfo');
    }

}
