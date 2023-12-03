<?php
namespace HITScoutingNL\Component\KampInfoImExport\Administrator\Common;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\Table\Table;


class KampInfoImporterExporter {

    const TABLE_PREFIX = 'HITScoutingNL\\Component\\KampInfoImExport\\Administrator\\Table\\';

    public function exportAll($year = 0) {
        $items = [];

        $projectTable = Table::getInstance('HitProjectTable', self::TABLE_PREFIX);
        if ($year == 0) {
            $items = $projectTable->find([]);
        } else {
            $items = $projectTable->find(['jaar' => $year]);
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
        $project = $hit->projects[0];
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
        foreach ($hit->projects as $project) {
            $this->importProject($project);
        }
    }

    private function importProject($project) {
        $table = $this->getHitTable('HitProject');

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
        $table = $this->getHitTable('HitPlaats');
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
        $table = $this->getHitTable('HitKamp');

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

    private function getHitTable($entity) {
        $table = Table::getInstance($entity . 'Table', self::TABLE_PREFIX);
        if (!$table) {
            throw new GenericDataException("Table '{$entity}' not found!", 500);
        }
        return $table;
    }

}
?>
