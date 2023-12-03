<?php

namespace HITScoutingNL\Component\KampInfoImExport\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Table\Table;

class ExportModel extends ListModel {

    const TABLE_PREFIX = 'HITScoutingNL\\Component\\KampInfoImExport\\Administrator\\Table\\';

    public function getItems() {
        $input = Factory::getApplication()->getInput();
        $jaar = $input->getInt('jaar', 0);

        $items = [];

        $projectTable = Table::getInstance('HitProjectTable', self::TABLE_PREFIX);
        if ($jaar == 0) {
            $items = $projectTable->find([]);
        } else {
            $items = $projectTable->find(['jaar' => $jaar]);
        }

        foreach ($items as $project) {
            $plaatsTable = Table::getInstance('HitPlaatsTable', self::TABLE_PREFIX);
            $project->plaatsen = $plaatsTable->find(['hitproject_id' => $project->id]);
            foreach ($project->plaatsen as $plaats) {
                $kampTable = Table::getInstance('HitKampTable', self::TABLE_PREFIX);
                $plaats->kampen = $kampTable->find(['hitsite_id' => $plaats->id]);
            }
        }

        return $items;
    }

}
