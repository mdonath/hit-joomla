<?php
namespace HITScoutingNL\Library\KampInfo\ImportExport;

\defined('_JEXEC') or die;

use HITScoutingNL\Library\KampInfo\ImportExport\Util;


class KampInfoImporter {

    public function importAlles($fileName) {
        if (!file_exists($fileName)) {
            throw new GenericDataException("File '{$fileName}' not found", 500);
        }

        $hit = json_decode(file_get_contents($fileName));
        return $this->importProjecten($hit);
    }

    public function importEenPlaats($fileName) {
        if (!file_exists($fileName)) {
            throw new GenericDataException("File '{$fileName}' not found", 500);
        }

        $hit = json_decode(file_get_contents($fileName));
        $this->importEnkelePlaats($hit);
    }

    private function importEnkelePlaats($hit) {
        // welk jaar?
        $project = $hit->projecten[0];
        $jaar = $project->jaar;

        $projectTable = $this->getTable('HitProject');
        $projectDB = $projectTable->find(['jaar' => $jaar]);
        $projectId = $projectDB[0]->id;

        // welke plaats
        $plaats = $project->plaatsen[0];
        $plaatsnaam = $plaats->naam;
        $plaats->naam = $plaats->naam . '-IMPORT';
        $plaats->hitproject_id = $projectId;

        $this->importPlaats($plaats);
    }

    private function importProjecten($hit) {
        foreach ($hit->projecten as $project) {
            $this->importProject($project);
        }
    }

    private function importProject($project) {
        $table = Util::getHitTable('Project');

        foreach ($project as $key => $value) {
            $table->$key = $value;
        }
        
        $table->id = null;
        $table->store();
        $project->id = $table->id;
        
        $this->importPlaatsen($project);

        unset($table);
        unset($project);
    }

    private function importPlaatsen($project) {
        foreach ($project->plaatsen as $plaats) {
            $plaats->hitproject_id = $project->id;
            $this->importPlaats($plaats);
        }
    }

    private function importPlaats($plaats) {
        $table = Util::getHitTable('Plaats');
        foreach ($plaats as $key => $value) {
            $table->$key = $value;
        }

        $table->id = null;
        $table->asset_id = null;
        $table->store();
        $plaats->id = $table->id;
        
        $this->importKampen($plaats);

        unset($table);
        unset($plaats);
    }

    private function importKampen($plaats) {
        foreach ($plaats->kampen as $kamp) {
            $kamp->hitsite_id = $plaats->id;
            $this->importKamp($kamp);
        }
    }

    private function importKamp($kamp) {
        $table = Util::getHitTable('Kamp');

        foreach ($kamp as $key => $value) {
            $table->$key = $value;
        }

        $table->id = null;
        $table->asset_id = null;
        $table->store();
        $kamp->id = $table->id;
        unset($table);
        unset($kamp);
    }

}
