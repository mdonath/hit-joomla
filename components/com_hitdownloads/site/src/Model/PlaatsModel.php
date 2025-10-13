<?php

namespace HITScoutingNL\Component\HitDownloads\Site\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Filesystem\Folder;


class PlaatsModel extends BaseDatabaseModel {

    public function getJaar() {
        $params = ComponentHelper::getParams('com_hitdownloads');
        return $params->get('huidigeActieveJaar', date('Y'));
    }

    public function getPlaats() {
        $input = Factory::getApplication()->getInput();
        $plaats = $input->getString('plaats', '');

        return $plaats;
    }

    public function getFiles($jaar, $plaats) {
        $folder = "/files/{$plaats}/{$jaar}/deelnemersinfo/";
        $files = Folder::files(JPATH_SITE . $folder);

        $result = [];
        foreach ($files as &$file) {
            $location = "{$folder}{$file}";
            $result[] = [
                'location' => $location,
                'naam' => $file,
                'type' => mime_content_type(JPATH_SITE . $location),
            ];
        }
        return $result;
    }

}
