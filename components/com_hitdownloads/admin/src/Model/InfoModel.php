<?php

namespace HITScoutingNL\Component\HitDownloads\Administrator\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\MVC\Model\ListModel;

use HITScoutingNL\Library\KampInfo\Metadata\ManifestUtil;


class InfoModel extends ListModel {

    public function getItems() {
        return ManifestUtil::getManifest($this->getDatabase(), 'com_hitdownloads');
    }

}
