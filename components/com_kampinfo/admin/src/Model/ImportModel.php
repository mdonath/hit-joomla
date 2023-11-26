<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;
use Joomla\Database\ParameterType;
use Joomla\Registry\Registry;

use HITScoutingNL\Component\KampInfo\Administrator\Helper\Mapper\CsvMapper;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\SolMapping;

class ImportModel extends AdminModel {

    public function getForm($data = [], $loadData = false) {
        $form = $this->loadForm(
            'com_kampinfo.import',
            'import',
            [
                'control' => 'jform',
                'load_data' => $loadData
            ]
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Importeert de inschrijfaantallen via CSV en werkt zo de bestaande gegevens bij.
     * 
     * @return boolean
     */
    public function importInschrijvingen() {
        $app = Factory::getApplication();

        $formdata = $app->getInput()->get('jform', array(), 'array');
        $jaar = $formdata["jaar"];

        if ($jaar < 2000) {
            $app->enqueueMessage("Geen jaartal opgegeven, was: '$jaar'");
            return false;
        }
        $app->enqueueMessage("Voor het jaartal $jaar");

        $file = self::getUploadedFile('import_inschrijvingen');

        if (!$file) {
            $app->enqueueMessage('Geen file geupload?!');
            return false;
        }

        $mapper = new CsvMapper(SolMapping::getInschrijvingenMapping($jaar));
        $rows = $mapper->read($file);

        $count = \count($rows);

        if ($count == 0) {
            $app->enqueueMessage('Geen rijen gevonden');
            return false;
        }
        $app->enqueueMessage("Aantal import rijen gevonden: $count");

        $count = self::updateInschrijvingen($rows,  $jaar);
        
        $msg = "Er zijn nu $count kampen gewijzigd met hun inschrijvingen t.o.v. de vorige keer";
        $app->enqueueMessage($msg);
        $this->updateLaatstBijgewerkt($jaar, 'INSC', $msg);
        
        return true;
    }

    private function getUploadedFile($fieldname) {
        $app = Factory::getApplication();

        // Make sure that file uploads are enabled in php
        if (!(bool) ini_get('file_uploads')) {
            $app->enqueueMessage(Text::_('File upload staat niet aan in configuratie'));
            return false;
        }
        $uploadedFile = $app->getInput()->files->get('jform', null, 'raw')[$fieldname];

        // If there is no uploaded file, we have a problem...
        if (!is_array($uploadedFile)) {
            $app->enqueueMessage("Er is geen bestand gekozen '$fieldname'");
            return false;
        }

        // Build the appropriate paths - nieuw
        $tmp_src = $uploadedFile['tmp_name'];
        $tmp_dest = Factory::getConfig()->get('tmp_path') . '/' . $uploadedFile['name'];

        // Move uploaded file
        if (File::upload($tmp_src, $tmp_dest) != 1) {
            return false;
        }

        return $tmp_dest;
    }

    private function updateInschrijvingen($rows, $jaar) {
        $app = Factory::getApplication();

        $db = Factory::getDbo();
        $count = 0;
        
        $postActionRows = [];
        
        foreach ($rows as $inschrijving) {
            $formulierNaamParts = [];
            // Als formulier naam een nummer tussen '((' en '))' heeft, dan is het een speciaal geval.
            // "HIT Plaats kampnaam (ki-id) ((SOL-id))"
            // Het nummer is het shantiFormuliernummer waar de betreffende gegevens bij opgeteld moeten worden.
            \preg_match("/HIT .* \(\((\d+)\)\)/", $inschrijving->formulierNaam, $formulierNaamParts);
            if (\count($formulierNaamParts) > 0) {
                // Het extra optellen stellen we uit tot nadat we alle gewone formulieren hebben gehad.
                $postActionRows[] = $inschrijving;
            } else {
                $fn = print_r($inschrijving,  true);
                // In de subgroepcategorie is bij ouderkind-kampen nu opgenomen hoeveel keer een inschrijving meetelt.
                // De naam is normaal "Koppelgroepje"
                // Bij ouderkind-kampen kan dit "Koppelgroepje (2)" of "Koppelgroepje (1)" zijn.
                // Bij de eerste variant (2) telt een inschrijving als twee (ouder+kind)
                // Bij de tweede variant (1) telt een inschrijving maar als één inschrijving, dit is dan één extra kind dat eigenlijk nog bij een ander koppel hoort
                $factor = $this->bepaalFactor($inschrijving->subgroepcategorie);

                $aantalDeelnemers = $factor * $inschrijving->aantalDeelnemers;

                $gereserveerd = $inschrijving->gereserveerd;
                if (empty($gereserveerd)) {
                    $gereserveerd = 0;
                }
                $gereserveerd = $factor * $gereserveerd;

                $aantalSubgroepen = $inschrijving->aantalSubgroepen;
                if (empty($aantalSubgroepen)) {
                    $aantalSubgroepen = 0;
                }
                $aantalSubgroepen = $factor * $aantalSubgroepen;
    
                $minimumAantalDeelnemers = $factor * $inschrijving->minimumAantalDeelnemers;
                $maximumAantalDeelnemers = $factor * $inschrijving->maximumAantalDeelnemers;

                $minimumLeeftijd = $inschrijving->minimumLeeftijd;
                $maximumLeeftijd = $inschrijving->maximumLeeftijd;

                $query = $db-> getQuery(true)
                    -> update($db->quoteName('#__kampinfo_hitcamp', 'c'))
                    -> join(
                        'INNER',
                        $db->quoteName('#__kampinfo_hitsite', 's'),
                        $db->quoteName('c.hitsite_id') .' = '. $db->quoteName('s.id')
                    )
                    -> join(
                        'INNER',
                        $db->quoteName('#__kampinfo_hitproject', 'p'),
                        $db->quoteName('s.hitproject_id') .' = '. $db->quoteName('p.id')
                    )
                    -> set($db->quoteName('c.aantalDeelnemers') . ' = :aantalDeelnemers')
                    -> bind(':aantalDeelnemers', $aantalDeelnemers, ParameterType::INTEGER)
                    -> set($db->quoteName('c.gereserveerd') . ' = :gereserveerd')
                    -> bind(':gereserveerd', $gereserveerd, ParameterType::INTEGER)
                    -> set($db->quoteName('c.aantalSubgroepen') . ' = :aantalSubgroepen')
                    -> bind(':aantalSubgroepen', $aantalSubgroepen, ParameterType::INTEGER)
                    -> set($db->quoteName('c.minimumAantalDeelnemers') . ' = :minimumAantalDeelnemers')
                    -> bind(':minimumAantalDeelnemers', $minimumAantalDeelnemers, ParameterType::INTEGER)
                    -> set($db->quoteName('c.maximumAantalDeelnemers') . ' = :maximumAantalDeelnemers')
                    -> bind(':maximumAantalDeelnemers', $maximumAantalDeelnemers, ParameterType::INTEGER)
                    -> set($db->quoteName('c.minimumLeeftijd') . ' = :minimumLeeftijd')
                    -> bind(':minimumLeeftijd', $minimumLeeftijd, ParameterType::INTEGER)
                    -> set($db->quoteName('c.maximumLeeftijd') . ' = :maximumLeeftijd')
                    -> bind(':maximumLeeftijd', $maximumLeeftijd, ParameterType::INTEGER)

                    -> where($db->quoteName('p.jaar') . ' = :jaar')
                    -> bind(':jaar', $jaar, ParameterType::INTEGER)
                    -> where($db->quoteName('c.shantiFormuliernummer') . ' = :shantiFormuliernummer') 
                    -> bind(':shantiFormuliernummer', $inschrijving->shantiFormuliernummer, ParameterType::INTEGER)
                ;

                try {
                    $db->setQuery($query);
                    $db->execute();
                } catch (\Exception $e) {
                    Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
                }

                // LET OP: alleen als het record ook daadwerkelijk gewijzigd is!
                $count += $db->getAffectedRows();
            }
        }
        
        // We gaan nu de speciale formulieren verwerken.
        foreach ($postActionRows as $inschrijving) {

            // Voor hoeveel personen telt een inschrijving mee 
            $factor = $this->bepaalFactor($inschrijving->subgroepcategorie);

            $aantalDeelnemers = $factor * $inschrijving->aantalDeelnemers;

            $gereserveerd = $inschrijving->gereserveerd;
            if (empty($gereserveerd)) {
                $gereserveerd = 0;
            }
            $gereserveerd = $factor * $gereserveerd;

            $aantalSubgroepen = $inschrijving->aantalSubgroepen;

            $minimumAantalDeelnemers = $factor * $inschrijving->minimumAantalDeelnemers;
            $maximumAantalDeelnemers = $factor * $inschrijving->maximumAantalDeelnemers;

            $formulierNaamParts = [];
            \preg_match("/HIT .* \(\((\d+)\)\)/", $inschrijving->formulierNaam, $formulierNaamParts);
            $shantiFormuliernummer = $formulierNaamParts[1];

            $query = $db-> getQuery(true)
                -> update($db->quoteName('#__kampinfo_hitcamp', 'c'))
                -> join(
                    'INNER',
                    $db->quoteName('#__kampinfo_hitsite', 's'),
                    $db->quoteName('c.hitsite_id') .' = '. $db->quoteName('s.id')
                )
                -> join(
                    'INNER',
                    $db->quoteName('#__kampinfo_hitproject', 'p'),
                    $db->quoteName('s.hitproject_id') .' = '. $db->quoteName('p.id')
                )
                -> set($db->quoteName('c.aantalDeelnemers') . ' = ' . $db->quoteName('c.aantalDeelnemers') . ' + :aantalDeelnemers')
                -> bind(':aantalDeelnemers', $aantalDeelnemers, ParameterType::INTEGER)
                -> set($db->quoteName('c.gereserveerd') . ' = ' . $db->quoteName('c.gereserveerd') . ' + :gereserveerd')
                -> bind(':gereserveerd', $gereserveerd, ParameterType::INTEGER)
                -> set($db->quoteName('c.aantalSubgroepen') . ' = ' . $db->quoteName('c.aantalSubgroepen') . ' + :aantalSubgroepen')
                -> bind(':aantalSubgroepen', $aantalSubgroepen, ParameterType::INTEGER)
                -> set($db->quoteName('c.minimumAantalDeelnemers') . ' = ' . $db->quoteName('c.minimumAantalDeelnemers') . ' + :minimumAantalDeelnemers')
                -> bind(':minimumAantalDeelnemers', $minimumAantalDeelnemers, ParameterType::INTEGER)
                -> set($db->quoteName('c.maximumAantalDeelnemers') . ' = ' . $db->quoteName('c.maximumAantalDeelnemers') . ' + :maximumAantalDeelnemers')
                -> bind(':maximumAantalDeelnemers', $maximumAantalDeelnemers, ParameterType::INTEGER)

                -> where($db->quoteName('p.jaar') . ' = :jaar')
                -> bind(':jaar', $jaar, ParameterType::INTEGER)
                -> where($db->quoteName('c.shantiFormuliernummer') . ' = :shantiFormuliernummer') 
                -> bind(':shantiFormuliernummer', $shantiFormuliernummer, ParameterType::INTEGER)
            ;

            try {
                $db->setQuery($query);
                $db->execute();
            } catch (\Exception $e) {
                Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
            }

            // LET OP: alleen als het record ook daadwerkelijk gewijzigd is!
            $count += $db->getAffectedRows();

        }
        return $count;
    }

    /**
     * In de subgroepcategorie is bij ouderkind-kampen nu opgenomen hoeveel keer een inschrijving meetelt.
     * De naam is normaal "Koppelgroepje"
     * Bij ouderkind-kampen zal dit "Koppelgroepje (2)" of "Koppelgroepje (1)" zijn.
     * Bij de eerste variant (2) telt een inschrijving als twee (ouder+kind)
     * Bij de tweede variant (1) telt een inschrijving maar als één inschrijving, dit is dan één extra kind dat eigenlijk nog bij een ander koppel hoort
     */
    private function bepaalFactor($subgroepcategorie) {
        $result = 1;
        if ($subgroepcategorie != "Koppelgroepje") {
            // Uitzoeken hoeveel mensen tegelijk met één formulier worden ingeschreven
                            
            $parts = [];
            preg_match("/Koppelgroepje \((\d+)\)/", $subgroepcategorie, $parts);
            if (\count($parts) > 0) {
                $result = (int) $parts[1];
            }
        }
        return $result;
    }

    /**
     * Werkt de tabel met logging mbt updates bij.
     * @param unknown $jaar
     * @param unknown $soort
     * @param unknown $melding
     */
    private function updateLaatstBijgewerkt($jaar, $soort, $melding) {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query->insert('#__kampinfo_downloads');
        $query->set("jaar=". (int)($db->escape($jaar)) .', soort = '. $db->quote($db->escape($soort)).', melding = '. $db->quote($db->escape($melding)));
        $db->setQuery($query);
        $db->execute();
    }
}
