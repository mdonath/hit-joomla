<?php
namespace HITScoutingNL\Library\KampInfo\ImportExport;

\defined('_JEXEC') or die;

use HITScoutingNL\Library\KampInfo\ImportExport\Util;


class KampInfoExporter {

    public function exportAlles($year = 0) {
        if ($year == 0) {
            $projectFilter = [];
        } else {
            $projectFilter = ['jaar' => $year];
        }

        $projectTable = Util::getHitTable('Project');
        $items = $projectTable->find($projectFilter);

        foreach ($items as $project) {
            $plaatsTable = Util::getHitTable('Plaats');
            $project->plaatsen = $plaatsTable->find(['hitproject_id' => $project->id]);
            foreach ($project->plaatsen as $plaats) {
                $kampTable = Util::getHitTable('Kamp');
                $plaats->kampen = $kampTable->find(['hitsite_id' => $plaats->id]);
            }
        }
        return $items;
    }

}
