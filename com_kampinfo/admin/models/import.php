<?php defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR.'/../com_kampinfo/helpers/kampinfo.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/../com_kampinfo/helpers/mapper.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/../com_kampinfo/helpers/SolDownload.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/../com_kampinfo/helpers/SolMapping.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/../com_kampinfo/helpers/SolConfiguration.php';

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
jimport('joomla.application.component.modeladmin');
jimport('joomla.filesystem.file');


/**
 * Import Model.
*/
class KampInfoModelImport extends JModelAdmin {

	/**
	 * Downloadt de inschrijfaantallen via de nieuwe REST call en importeert deze om zo de
	 * huidige records weer bij te werken.
	 * 
	 * @return boolean
	 */
	public function downloadInschrijvingen() {
		$app = JFactory::getApplication();

		$params = JComponentHelper::getParams('com_kampinfo');

		// Voor welk jaar staat in de configuratie
		$projectId = $params->get('huidigeActieveJaar');
		if ($projectId < 0) {
			$app->enqueueMessage('Geen jaartal opgegeven');
			return false;
		}

		$project = self::getHitProject($projectId);
		$eventId = $project->shantiEvenementId;
		$app->enqueueMessage("Shanti evenement: " . $eventId);

		$jaar = $project->jaar;
		$app->enqueueMessage("Jaar: " . $jaar);
		
		// Via JSON downloaden
		$solConfig = new SolConfiguration($params);
		$sol = new SolDownload();
		$result = $sol->downloadInschrijvingen($eventId, $solConfig);

		// Importeer gegevens
		$mapper = new JsonMapper(SolMapping::getInschrijvingenMapping($jaar, "json"));
		$rows = $mapper->readList($result);

		$count = count($rows);

		$app->enqueueMessage('aantal import rijen gevonden: ' . $count);

		$count = self::updateInschrijvingen($rows,  $jaar);

		$msg = "Er zijn nu $count kampen bijgewerkt met hun inschrijvingen";
		$app->enqueueMessage($msg);
		$this->updateLaatstBijgewerkt($jaar, 'INSC', $msg);
		
		return true;
	}

	/**
	 * Importeert de inschrijfaantallen via CSV en werkt zo de bestaande gegevens bij.
	 * 
	 * @return boolean
	 */
	public function importInschrijvingen() {
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$formdata = $jinput->get('jform', array(), 'array');
		$jaar = $formdata["jaar"];

		if ($jaar < 2000) {
			$app->enqueueMessage('Geen jaartal opgegeven');
			return false;
		}
		$app->enqueueMessage('Voor het jaartal ' . $jaar);

		$file = self::getUploadedFile('import_inschrijvingen');

		if (!$file) {
			$app->enqueueMessage('Geen file geupload?!');
			return false;
		}
		$app->enqueueMessage('File: ' . $file);

		$mapper = new CsvMapper(SolMapping::getInschrijvingenMapping($jaar));
		$rows = $mapper->read($file);

		$count = count($rows);

		if ($count == 0) {
			$app->enqueueMessage('geen rijen gevonden');
			return false;
		}
		$app->enqueueMessage('aantal import rijen gevonden: ' . $count);

		$count = self::updateInschrijvingen($rows,  $jaar);
		
		$msg = "Er zijn nu $count kampen gewijzigd met hun inschrijvingen t.o.v. de vorige keer";
		$app->enqueueMessage($msg);
		$this->updateLaatstBijgewerkt($jaar, 'INSC', $msg);
		
		return true;
	}

	/**
	 * Downloadt de deelnemerinschrijfgegevens en vervangt de huidige gegevens.
	 * @return boolean
	 */
	public function downloadDeelnemergegevens() {
		$app = JFactory::getApplication();

		$params =JComponentHelper::getParams('com_kampinfo');
	
		// Voor welk jaar staat in de configuratie
		$projectId = $params->get('huidigeActieveJaar');
		if ($projectId < 0) {
			$app->enqueueMessage('Geen jaartal opgegeven');
			return false;
		}

		$project = self::getHitProject($projectId);
		$eventId = $project->shantiEvenementId;
		$app->enqueueMessage("Shanti evenement: " . $eventId);

		$jaar = $project->jaar;
		$app->enqueueMessage("Jaar: " . $jaar);
	
		// Via Soap Downloaden
		$solConfig = new SolConfiguration($params);
		$sol = new SolDownload();
		$result = $sol->downloadDeelnemers($eventId, $solConfig);

		// Importeer gegevens
		$mapper = new JsonMapper(SolMapping::getDeelnemergegevensMapping($jaar, "json"));
		$rows = $mapper->readList($result);

		$count = count($rows);
		$app->enqueueMessage('aantal import rijen gevonden: ' . $count);

		// Gegevens van het ingelezen jaar moeten eerst verwijderd worden, anders blijven geannuleerde deelnemers in de database staan.
		$count = self::updateDeelnemergegevens($rows,  $jaar);
		$msg = "Er zijn nu $count deelnemers toegevoegd.";

		$app->enqueueMessage($msg);
		$this->updateLaatstBijgewerkt($jaar, 'DEEL', $msg);
		
		return true;
	}

	/**
	 * Importeert de deelnemerinschrijfgegevens via een upload van een XML (let op: XML!).
	 */
	public function importDeelnemergegevens() {
		$app = JFactory::getApplication();
		
		$jinput = $app->input;
		$formdata = $jinput->get('jform', array(), 'array');
		$jaar = $formdata["jaar"];

		if ($jaar < 2000) {
			$app->enqueueMessage('Geen jaartal opgegeven');
			return false;
		}
		$app->enqueueMessage('Voor het jaartal ' . $jaar);

		$file = self::getUploadedFile('import_deelnemers');

		if (!$file) {
			$app->enqueueMessage('Geen file geupload?!');
			return false;
		}
		$app->enqueueMessage('File: ' . $file);

		$mapper = new CsvMapper(SolMapping::getDeelnemergegevensMapping($jaar));
		$rows = $mapper->read($file);

		$count = count($rows);
		
		if ($count == 0) {
			$app->enqueueMessage('geen rijen gevonden');
			return false;
		}
		$app->enqueueMessage('aantal import rijen gevonden: ' . $count);
		
		$count = self::updateDeelnemergegevens($rows,  $jaar);
		$app->enqueueMessage("Er zijn nu $count deelnemers toegevoegd.");

		return true;
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
							
			$parts = array();
			preg_match("/Koppelgroepje \((\d+)\)/", $subgroepcategorie, $parts);
			if (count($parts) > 0) {
				$result = (int) $parts[1];
			}
		}
		return $result;
	}

	private function updateInschrijvingen($rows, $jaar) {
		$db = JFactory::getDbo();
		$count = 0;
		
		$postActionRows = array();
		
		foreach ($rows as $inschrijving) {
			$aantalDeelnemers = $inschrijving->aantalDeelnemers;
			$gereserveerd = $inschrijving->gereserveerd;
			$aantalSubgroepen = $inschrijving->aantalSubgroepen;


			$minimumAantalDeelnemers = $inschrijving->minimumAantalDeelnemers;
			$maximumAantalDeelnemers = $inschrijving->maximumAantalDeelnemers;
			$minimumLeeftijd = $inschrijving->minimumLeeftijd;
			$maximumLeeftijd = $inschrijving->maximumLeeftijd;
				
			if (empty($gereserveerd)) {
				$gereserveerd = 0;
			}
			if (empty($aantalSubgroepen)) {
				$aantalSubgroepen = 0;
			}
							
			$formulierNaamParts = array();
			// Als formulier naam een nummer tussen '{' en '}' heeft, dan is het een speciaal geval.
			// mdo@20191226: accolades mogen niet meer in de bestandsnaam voorkomen. Daarom nu daarvoor in de plaats
			// dubbele haakjes:
			// "HIT Plaats kampnaam (ki-id) ((SOL-id))" <--- nieuw
			// en het WAS dus voorheen:
			// "HIT Plaats kampnaam (ki-id) {SOL-id}" <--- oud
			
			// Het nummer is het shantiFormuliernummer waar de betreffende gegevens bij opgeteld moeten worden.
			preg_match("/HIT .* \(\((\d+)\)\)/", $inschrijving->formulierNaam, $formulierNaamParts);
			if (count($formulierNaamParts) > 0) {
				// Het extra optellen stellen we uit tot nadat we alle gewone formulieren hebben gehad.
				$postActionRows[] = $inschrijving;
			} else {
				// In de subgroepcategorie is bij ouderkind-kampen nu opgenomen hoeveel keer een inschrijving meetelt.
				// De naam is normaal "Koppelgroepje"
				// Bij ouderkind-kampen kan dit "Koppelgroepje (2)" of "Koppelgroepje (1)" zijn.
				// Bij de eerste variant (2) telt een inschrijving als twee (ouder+kind)
				// Bij de tweede variant (1) telt een inschrijving maar als één inschrijving, dit is dan één extra kind dat eigenlijk nog bij een ander koppel hoort

				$factor = $this->bepaalFactor($inschrijving->subgroepcategorie);

				$query = "UPDATE #__kampinfo_hitcamp c, #__kampinfo_hitsite s, #__kampinfo_hitproject p SET"
						. "	 c.aantalDeelnemers = " .			$factor * (int)($db->escape($aantalDeelnemers))
						. ", c.gereserveerd = " .				$factor * (int)($db->escape($gereserveerd))
						. ", c.aantalSubgroepen = " .			(int)($db->escape($aantalSubgroepen))
						. ", c.minimumAantalDeelnemers = " .	$factor * (int)($db->escape($minimumAantalDeelnemers))
						. ", c.maximumAantalDeelnemers = " .	$factor * (int)($db->escape($maximumAantalDeelnemers))
						. ", c.minimumLeeftijd = " . (int)($db->escape($minimumLeeftijd))
						. ", c.maximumLeeftijd = " . (int)($db->escape($maximumLeeftijd))
						. " WHERE "
						. " c.hitsite_id = s.id AND s.hitproject_id = p.id AND p.jaar = ". ($db->escape($jaar))
						. " AND c.shantiFormuliernummer = " . (int)($db->escape($inschrijving->shantiFormuliernummer))
						;
				$db->setQuery($query);
				$db->execute();
				// Check for a database error.
				if ($db->getErrorNum()) {
					JError::raiseWarning(500, $db->getErrorMsg());
				}
				// LET OP: alleen als het record ook daadwerkelijk gewijzigd is!
				$count += $db->getAffectedRows();
			}
		}
		
		// We gaan nu de speciale formulieren verwerken.
		foreach ($postActionRows as $inschrijving) {
			$gereserveerd = $inschrijving->gereserveerd;
				
			$formulierNaamParts = array();
			preg_match("/HIT .* \(\((\d+)\)\)/", $inschrijving->formulierNaam, $formulierNaamParts);
			
			if (empty($gereserveerd)) {
				$gereserveerd = 0;
			}

			// Voor hoeveel personen telt een inschrijving mee 
			$factor = $this->bepaalFactor($inschrijving->subgroepcategorie);

			$query = "UPDATE #__kampinfo_hitcamp c, #__kampinfo_hitsite s, #__kampinfo_hitproject p SET"
					. "	 c.aantalDeelnemers = c.aantalDeelnemers + " .					$factor * (int)($db->escape($inschrijving->aantalDeelnemers))
					. ", c.gereserveerd = c.gereserveerd + " .							$factor * (int)($db->escape($gereserveerd))
					. ", c.aantalSubgroepen = c.aantalSubgroepen + " .					(int)($db->escape($inschrijving->aantalSubgroepen))
					. ", c.minimumAantalDeelnemers = c.minimumAantalDeelnemers + " .	$factor * (int)($db->escape($inschrijving->minimumAantalDeelnemers))
					. ", c.maximumAantalDeelnemers = c.maximumAantalDeelnemers + " .	$factor * (int)($db->escape($inschrijving->maximumAantalDeelnemers))
				. " WHERE "
					. " c.hitsite_id = s.id AND s.hitproject_id = p.id AND p.jaar = ". ($db->escape($jaar))
					. " AND c.shantiFormuliernummer = " . (int)($db->escape($formulierNaamParts[1]))
			;
			$db->setQuery($query);
			$db->execute();
			// Check for a database error.
			if ($db->getErrorNum()) {
				JError::raiseWarning(500, $db->getErrorMsg());
			}
			// LET OP: alleen als het record ook daadwerkelijk gewijzigd is!
			$count += $db->getAffectedRows();
		}
		return $count;
	}
	
	private function verwijderDeelnemergegevens($jaar) {
		$db = JFactory::getDbo();
		$query = "DELETE FROM #__kampinfo_deelnemers "
				. "WHERE jaar = ". ($db->escape($jaar));
		
		$db->setQuery($query);
		$db->query();
		
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
	}

	private function updateDeelnemergegevens($rows, $jaar) {
		$db = JFactory::getDbo();
		
		// Ouwe meuk weggooien
		self::verwijderDeelnemergegevens($jaar);
		$deelnemerNummers = array(); // FIXME uitzoeken of deelnemerNummers inderdaad vaker kunnen voorkomen
		$count = 0;
		foreach ($rows as $deelnemer) {
			if (!in_array($deelnemer->dlnnr, $deelnemerNummers, true) && isset($deelnemer->formulier)) {
				$deelnemerNummers[] = $deelnemer->dlnnr;
				
				$formulier = array();
				preg_match("/HIT (\w+) (.*) \((\d+)\)/", $deelnemer->formulier, $formulier);

				if (count($formulier) != 0) {
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query->insert('#__kampinfo_deelnemers');
					// FIXME uitzoeken of hier nog conversie naar UTC nodig is.
					$query->set(
							'  jaar = '. (int)($db->escape($jaar)) .
							', dlnnr = '. (int)($db->escape($deelnemer->dlnnr)) .
							', herkomst = '. $db->quote($db->escape($deelnemer->plaats .', '. $deelnemer->land)) .
							', leeftijd = '. (int)($db->escape($deelnemer->leeftijd)) .
							', geslacht = '. $db->quote($db->escape($deelnemer->geslacht)) .
							', datumInschrijving = '. $db->quote($deelnemer->datumInschrijving->format('Y-m-d')) .
							', hitsite = ' . $db->quote($db->escape(strtolower($formulier[1]))) .
							', hitcamp = ' . $db->quote($db->escape($formulier[2])) .
							', hitcampId = ' . (int)($db->escape($formulier[3]))
					);
					$db->setQuery($query);
					$db->query();
					$count++;
				} else {
					// oude formulieren hebben het hitcampId niet tussen haakjes
					$formulier = array();
					preg_match("/HIT (\w+) (.*)/", $deelnemer->formulier, $formulier);
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query->insert('#__kampinfo_deelnemers');
					$query->set(
							'  jaar = '. (int)($db->escape($jaar)) .
							', dlnnr = '. (int)($db->escape($deelnemer->dlnnr)) .
							', herkomst = '. $db->quote($db->escape($deelnemer->plaats .', '. $deelnemer->land)) .
							', leeftijd = '. (int)($db->escape($deelnemer->leeftijd)) .
							', geslacht = '. $db->quote($db->escape($deelnemer->geslacht)) .
							', datumInschrijving = '. $db->quote($deelnemer->datumInschrijving->format('Y-m-d')) .
							', hitsite = ' . $db->quote($db->escape(strtolower($formulier[1]))) .
							', hitcamp = ' . $db->quote($db->escape($formulier[2])) .
							', hitcampId = null'
					);
					$db->setQuery($query);
					$db->query();
					$count++;
				}
			}
		}
		return $count;
	}

	protected function getUploadedFile($fieldname) {
		$app = JFactory::getApplication();
		// Make sure that file uploads are enabled in php
		if (!(bool) ini_get('file_uploads')) {
			$app->enqueueMessage(JText::_('file upload staat niet aan in configuratie'));
			return false;
		}

		$jFileInput = new JInput($_FILES);
		$uploadedFile = $jFileInput->get('jform',array(),'array');

		// If there is no uploaded file, we have a problem...
		if (!is_array($uploadedFile)) {
			JError::raiseWarning('', 'No file was selected.');
			return false;
		}

		// Build the appropriate paths
		$config		= JFactory::getConfig();
		$tmp_src	= $uploadedFile['tmp_name'][$fieldname];
		$tmp_dest	= $config->get('tmp_path') . '/' . $uploadedFile['name'][$fieldname];

		// Move uploaded file
		$uploaded = JFile::upload($tmp_src, $tmp_dest);

		return $tmp_dest;
	}


	/**
	 * Werkt de tabel met logging mbt updates bij.
	 * @param unknown $jaar
	 * @param unknown $soort
	 * @param unknown $melding
	 */
	private function updateLaatstBijgewerkt($jaar, $soort, $melding) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->insert('#__kampinfo_downloads');
		$query->set("jaar=". (int)($db->escape($jaar)) .', soort = '. $db->quote($db->escape($soort)).', melding = '. $db->quote($db->escape($melding)));
		$db->setQuery($query);
		$db->query();
	}

	public function getForm($data = array(), $loadData = true) {
		return self::loadForm(
				'com_kampinfo.import'
				,	'import'
				,	array('control' => 'jform', 'load_data' => $loadData));
	}

	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_kampinfo.import.data', array());
		if (empty($data)) {
			$data = self::getItem();
		}
		return $data;
	}

	public function getTable($type = 'HitCamp', $prefix = 'KampInfoTable', $config = array ()) {
		return JTable::getInstance($type, $prefix, $config);
	}

	private function getHitProject($projectId) {
		$db = JFactory::getDBO();
	
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__kampinfo_hitproject p');
		$query->where('(p.id = ' . (int) ($db->escape($projectId)) . ')');
	
		$db->setQuery($query);
		$project = $db->loadObjectList();
	
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		return $project[0];
	}
	
	private function getJaarVanProject($projectId) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
	
		$query->select('p.jaar');
		$query->from('#__kampinfo_hitproject p');
		$query->where('p.id = ' . ($db->escape($projectId)));
	
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	
	private function getShantiEvenementId($projectId) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
	
		$query->select('p.shantiEvenementId');
		$query->from('#__kampinfo_hitproject p');
		$query->where('p.id = ' . ($db->escape($projectId)));
	
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
}
