<?php

namespace HITScoutingNL\Component\KampInfoImExport\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;

use HITScoutingNL\Component\KampInfoImExport\Administrator\Common\KampInfoImporter;


class ImportModel extends AdminModel {

    public function getForm($data = array(), $loadData = true) {
        return self::loadForm(
            'com_kampinfoimexport.import',
            'import',
            [
                'control' => 'jform',
                'load_data' => $loadData
            ]
        );
}   

    public function importAlles() {
        $app = Factory::getApplication();

        $file = self::getUploadedFile('import_file');
        if (!$file) {
            $app->enqueueMessage('Geen file geüpload?!');
            return false;
        }
        $app->enqueueMessage('File: ' . $file);

        try {
            $importer = new KampInfoImporter();

            $importer->importAlles($file);
            $app->enqueueMessage('Alles geïmporteerd');
        } catch (GenericDataException $e) {
            $app->enqueueMessage("Error {$e}");
        }
    }

    public function importEenPlaats() {
        $app = Factory::getApplication();

        $file = self::getUploadedFile('import_file');
        if (!$file) {
            $app->enqueueMessage('Geen file geüpload?!');
            return false;
        }
        $app->enqueueMessage('File: ' . $file);

        try {
            $importer = new KampInfoImporter();
            $importer->importEnkelePlaats($hit);
            $app->enqueueMessage('Enkele plaats geïmporteerd');
        } catch (GenericDataException $e) {
            $app->enqueueMessage("Error {$e}");
        }
    }

    private function importProjecten($hit) {
        foreach ($hit->projects as $project) {
            $this->importProject($project);
        }
    }

    private function importProject($project) {
        $table = $this->getTable('HitProject');
        if ($table) {
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
    }

    private function importPlaatsen($project) {
        foreach ($project->plaatsen as $plaats) {
            $plaats->hitproject_id = $project->id;
            $this->importPlaats($plaats);
        }
    }

    private function importPlaats($plaats) {
        $table = $this->getHitTable('HitPlaats');
        if ($table) {
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
    }

    private function importKampen($plaats) {
        foreach ($plaats->kampen as $kamp) {
            $kamp->hitsite_id = $plaats->id;
            $this->importKamp($kamp);
        }
    }

    private function importKamp($kamp) {
        $table = $this->getHitTable('HitKamp');
        if ($table) {
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

    private function getHitTable($entity) {
        $table = Table::getInstance($entity . 'Table', self::TABLE_PREFIX);
        if (!$table) {
            $app = Factory::getApplication();
            $app->enqueueMessage('Geen Table voor '. $entity. ' gevonden!');
            return false;
        }
        return $table;
    }

    private function getUploadedFile($fieldname) {
        $app = Factory::getApplication();

        // Make sure that file uploads are enabled in php
        if (!(bool) ini_get('file_uploads')) {
            $app->enqueueMessage(Text::_('file_uploads staat niet aan in PHP configuratie'));
            return false;
        }

        $uploadedFile = $app->getInput()->files->get('import_file', null, 'raw');

        // If there is no uploaded file, we have a problem...
        if (!is_array($uploadedFile)) {
            $app->enqueueMessage('No file was selected.');
            return false;
        }

        // Build the appropriate paths
        $tmp_path = Factory::getConfig()->get('tmp_path');
        $tmp_src = $uploadedFile['tmp_name'];
        $tmp_dest = $tmp_path . '/' . $uploadedFile['name'];

        // Move uploaded file
        if (File::upload($tmp_src, $tmp_dest) != 1) {
            return false;
        }

        return $tmp_dest;
    }

}
