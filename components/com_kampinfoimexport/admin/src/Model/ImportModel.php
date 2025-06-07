<?php

namespace HITScoutingNL\Component\KampInfoImExport\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;

use HITScoutingNL\Library\KampInfo\ImportExport\KampInfoImporter;


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
            $importer = new KampInfoImporterExporter();
            $importer->importEnkelePlaats($hit);
            $app->enqueueMessage('Enkele plaats geïmporteerd');
        } catch (GenericDataException $e) {
            $app->enqueueMessage("Error {$e}");
        }
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
