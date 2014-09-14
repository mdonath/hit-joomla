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


class SolConfig {
	
	
}
/**
 * Import Model.
*/
class KampInfoModelImport extends JModelAdmin {

	/**
	 * Downloadt de inschrijfaantallen via SOAP en importeert deze om zo de huidige records weer bij te werken.
	 * 
	 * @return boolean
	 */
	public function downloadInschrijvingen() {
		$app = JFactory::getApplication();

		$params = &JComponentHelper::getParams('com_kampinfo');

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
		$result = $sol->downloadEvent($solConfig->user, $solConfig->password, $solConfig->role, $solConfig->sui, $solConfig->keypriv, $solConfig->wsdl, $eventId, 'forms');
		
		// Importeer gegevens
		$mapper = new XmlMapper(SolMapping::getInschrijvingenMapping($jaar));
		$rows = $mapper->readString($result);
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

		$params = &JComponentHelper::getParams('com_kampinfo');
	
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
		$result = $sol->downloadEvent($solConfig->user, $solConfig->password, $solConfig->role, $solConfig->sui, $solConfig->keypriv, $solConfig->wsdl, $eventId, 'participants');

		// Importeer gegevens
		$mapper = new XmlMapper(SolMapping::getDeelnemergegevensMapping($jaar));
		$rows = $mapper->readString($result);

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
	 * Importeert de deelnemerinschrijfgegevens via een upload van een CSV.
	 */
	public function importDeelnemergegevens() {
		$app = JFactory::getApplication();
		$app->enqueueMessage("Tijdelijks uitgeschakeld");
		return true;
		
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

		$count = self::updateDeelnemergegevens($rows,  $jaar);
		$app->enqueueMessage("Er zijn nu $count deelnemers toegevoegd.");
		return true;
	}

	private function updateInschrijvingen($rows, $jaar) {
		$db = JFactory :: getDbo();
		$count = 0;
		foreach ($rows as $inschrijving) {
			$aantalDeelnemers = $inschrijving->aantalDeelnemers;
			$gereserveerd = $inschrijving->gereserveerd;
			$aantalSubgroepen = $inschrijving->aantalSubgroepen;
			if (empty($gereserveerd)) {
				$gereserveerd = 0;
			}
			$query = "UPDATE #__kampinfo_hitcamp c, #__kampinfo_hitsite s, #__kampinfo_hitproject p SET"
					. "	 c.aantalDeelnemers = " .	(int)($db->getEscaped($aantalDeelnemers))
					. ", c.gereserveerd = " .		(int)($db->getEscaped($gereserveerd))
					. ", c.aantalSubgroepen = " .	(int)($db->getEscaped($aantalSubgroepen))
					. " WHERE "
					. " c.hitsite_id = s.id AND s.hitproject_id = p.id AND p.jaar = ". ($db->getEscaped($jaar))
					. " AND c.shantiFormuliernummer = " . (int)($db->getEscaped($inschrijving->shantiFormuliernummer))
					;
			$db->setQuery($query);
			$db->execute();
			// Check for a database error.
			if ($db->getErrorNum()) {
				JError :: raiseWarning(500, $db->getErrorMsg());
			}
			// LET OP: alleen als het record ook daadwerkelijk gewijzigd is!
			$count += $db->getAffectedRows();
		}
		return $count;
	}
	
	private function verwijderDeelnemergegevens($jaar) {
		$db = JFactory :: getDbo();
		$query = "DELETE FROM #__kampinfo_deelnemers "
				. "WHERE jaar = ". ($db->getEscaped($jaar));
		
		$db->setQuery($query);
		$db->query();
		
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
	}

	private function updateDeelnemergegevens($rows, $jaar) {
		$db = JFactory :: getDbo();
		
		// Ouwe meuk weggooien
		self::verwijderDeelnemergegevens($jaar);
		$deelnemerNummers = array();
		$count = 0;
		foreach ($rows as $deelnemer) {
			if (!in_array($deelnemer->dlnnr, $deelnemerNummers)) {
				$deelnemerNummers[] = $deelnemer->dlnnr;
				
				$formulier = array();
				preg_match("/HIT (\w+) (.*) \((\d+)\)/", $deelnemer->formulier, $formulier);

				if (count($formulier) != 0) {
					$db = JFactory :: getDbo();
					$query = $db->getQuery(true);
					$query->insert('#__kampinfo_deelnemers');
					$query->set(
							'  jaar = '. (int)($db->getEscaped($jaar)) .
							', dlnnr = '. (int)($db->getEscaped($deelnemer->dlnnr)) .
							', herkomst = '. $db->quote($db->getEscaped($deelnemer->plaats .', '. $deelnemer->land)) .
							', leeftijd = '. (int)($db->getEscaped($deelnemer->leeftijd)) .
							', geslacht = '. $db->quote($db->getEscaped($deelnemer->geslacht)) .
							', datumInschrijving = '. $db->quote($deelnemer->datumInschrijving->format('Y-m-d')) .
							', hitsite = ' . $db->quote($db->getEscaped(strtolower($formulier[1]))) .
							', hitcamp = ' . $db->quote($db->getEscaped($formulier[2])) .
							', hitcampId = ' . (int)($db->getEscaped($formulier[3]))
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
		$db = JFactory :: getDbo();
		$query = $db->getQuery(true);
		$query->insert('#__kampinfo_downloads');
		$query->set("jaar=". (int)($db->getEscaped($jaar)) .', soort = '. $db->quote($db->getEscaped($soort)).', melding = '. $db->quote($db->getEscaped($melding)));
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
		return JTable :: getInstance($type, $prefix, $config);
	}

	private function getHitProject($projectId) {
		$db = JFactory :: getDBO();
	
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__kampinfo_hitproject p');
		$query->where('(p.id = ' . (int) ($db->getEscaped($projectId)) . ')');
	
		$db->setQuery($query);
		$project = $db->loadObjectList();
	
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
		return $project[0];
	}
	
	private function getJaarVanProject($projectId) {
		$db = JFactory :: getDbo();
		$query = $db->getQuery(true);
	
		$query->select('p.jaar');
		$query->from('#__kampinfo_hitproject p');
		$query->where('p.id = ' . ($db->getEscaped($projectId)));
	
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	
	private function getShantiEvenementId($projectId) {
		$db = JFactory :: getDbo();
		$query = $db->getQuery(true);
	
		$query->select('p.shantiEvenementId');
		$query->from('#__kampinfo_hitproject p');
		$query->where('p.id = ' . ($db->getEscaped($projectId)));
	
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
}
