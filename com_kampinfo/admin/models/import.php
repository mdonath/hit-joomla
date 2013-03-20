<?php defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR.'/../com_kampinfo/helpers/mapper.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/../com_kampinfo/helpers/SolDownload.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/../com_kampinfo/helpers/kampinfo.php';

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
jimport('joomla.application.component.modeladmin');
jimport('joomla.filesystem.file');


/**
 * Import Model.
*/
class KampInfoModelImport extends JModelAdmin
{

	/**
	 * Downloadt de kampgegevens uit SOL en overschrijft de huidige gegevens.
	 */
	public function downloadKampgegevens() {
		$app = JFactory::getApplication();

		$params = &JComponentHelper::getParams('com_kampinfo');

		// Voor welk jaar staat in de configuratie
		$jaar = $params->get('downloadJaar');
		if ($jaar < 2000) {
			$app->enqueueMessage('Geen jaartal opgegeven');
			return false;
		}

		// Via Soap Downloaden
		$user = $params->get('soapUser');
		$password = $params->get('soapPassword');
		$role = $params->get('soapRolemask');
		$sui = $params->get('soapSui');
		$keypriv = $params->get('soapPrivateKey');
		$wsdl = $params->get('soapWsdl');
		$frm_id = $params->get('downloadFormIdKampRegistratie');
		$parts = explode(',', $params->get('downloadKampRegistratiePartsIds'));
			
		$sol = new SolDownload();
		$result = $sol->downloadForm($user, $password, $role, $sui, $keypriv, $wsdl, $frm_id, $parts);

		// Importeer gegevens
		$mapper = new XmlMapper(self::getKampgegevensMapping());
		$rows = $mapper->readString($result);

		$result = self::verwijderEnImporteerKampgegevens($app, $rows, $jaar);
		$this->updateLaatstBijgewerkt($jaar, 'KAMP', 'Aantal rijen vervangen: ' . count($rows));
		return $result;
	}

	/**
	 * Importeert de kampgegevens via een upload van een CSV.
	 */
	public function importKampgegevens() {
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$formdata = $jinput->get('jform', array(), 'array');
		$jaar = $formdata["jaar"];

		if ($jaar < 2000) {
			$app->enqueueMessage('Geen jaartal opgegeven');
			return false;
		}
		$app->enqueueMessage('Voor het jaartal ' . $jaar);

		$file = self::getUploadedFile('import_kampgegevens');

		if (!$file) {
			$app->enqueueMessage('Geen file geupload?!');
			return false;
		}
		$app->enqueueMessage('File: ' . $file);

		$mapper = new CsvMapper(self::getKampgegevensMapping());
		$rows = $mapper->read($file);

		return self::verwijderEnImporteerKampgegevens($app, $rows, $jaar);
	}

	public function downloadInschrijvingen() {
		$app = JFactory::getApplication();

		$params = &JComponentHelper::getParams('com_kampinfo');

		// Voor welk jaar staat in de configuratie
		$jaar = $params->get('downloadJaar');
		if ($jaar < 2000) {
			$app->enqueueMessage('Geen jaartal opgegeven');
			return false;
		}

		// Via Soap Downloaden
		$user = $params->get('soapUser');
		$password = $params->get('soapPassword');
		$role = $params->get('soapRolemask');
		$sui = $params->get('soapSui');
		$keypriv = $params->get('soapPrivateKey');
		$wsdl = $params->get('soapWsdl');

		$eventId = $params->get('downloadEventIdInschrijvingen');

		$sol = new SolDownload();
		$result = $sol->downloadEvent($user, $password, $role, $sui, $keypriv, $wsdl, $eventId, 'forms');

		// Importeer gegevens
		$mapper = new XmlMapper(self::getInschrijvingenMapping());
		$rows = $mapper->readString($result);
		$count = count($rows);

		$app->enqueueMessage('aantal import rijen gevonden: ' . $count);

		$count = self::updateInschrijvingen($rows,  $jaar);
		$msg = "Er zijn nu $count kampen bijgewerkt met hun inschrijvingen";
		$app->enqueueMessage($msg);
		$this->updateLaatstBijgewerkt($jaar, 'INSC', $msg);
		return true;
	}

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

		$mapper = new CsvMapper(self::getInschrijvingenMapping());
		$rows = $mapper->read($file);

		$count = count($rows);

		if ($count == 0) {
			$app->enqueueMessage('geen rijen gevonden');
			return false;
		}
		$app->enqueueMessage('aantal import rijen gevonden: ' . $count);

		$count = self::updateInschrijvingen($rows,  $jaar);
		$app->enqueueMessage("Er zijn nu $count kampen gewijzigd met hun inschrijvingen t.o.v. de vorige keer");
		return true;
	}

	/**
	 * https://sol.scouting.nl/index.php
	 * ?task=as_event
	 * &action=participants
	 * &evt_id=3331
	 * &button=export
	 * &export=true
	 * &tev_id=1
	 * &partViewRights=1
	 * @return boolean
	 */
	public function downloadDeelnemergegevens() {
		$app = JFactory::getApplication();
	
		$params = &JComponentHelper::getParams('com_kampinfo');
	
		// Voor welk jaar staat in de configuratie
		$jaar = $params->get('downloadJaar');
		if ($jaar < 2000) {
			$app->enqueueMessage('Geen jaartal opgegeven');
			return false;
		}
	
		// Via Soap Downloaden
		$user = $params->get('soapUser');
		$password = $params->get('soapPassword');
		$role = $params->get('soapRolemask');
		$sui = $params->get('soapSui');
		$keypriv = $params->get('soapPrivateKey');
		$wsdl = $params->get('soapWsdl');
	
		$eventId = $params->get('downloadEventIdInschrijvingen');
	
		$sol = new SolDownload();
		$result = $sol->downloadEvent($user, $password, $role, $sui, $keypriv, $wsdl, $eventId, 'participants');
	print_r($result);

	// Importeer gegevens
		$mapper = new XmlMapper(self::getDeelnemergegevensMapping($jaar));
		$rows = $mapper->readString($result);
	print_r($rows);
		$count = count($rows);
		
		$app->enqueueMessage('aantal import rijen gevonden: ' . $count);
		
		$count = self::updateDeelnemergegevens($rows,  $jaar);
		$msg = "Er zijn nu $count deelnemers toegevoegd.";

		$app->enqueueMessage($msg);
		$this->updateLaatstBijgewerkt($jaar, 'DEEL', $msg);
		
		return true;
	}

	/**
	 * Importeert de deelnemers via een upload van een CSV.
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

		$mapper = new CsvMapper(self::getDeelnemergegevensMapping($jaar));
		$rows = $mapper->read($file);

		$count = self::updateDeelnemergegevens($rows,  $jaar);
		$app->enqueueMessage("Er zijn nu $count deelnemers toegevoegd.");
		return true;
	}

	private function verwijderEnImporteerKampgegevens($app, $rows, $jaar) {
		$count = count($rows);

		if ($count == 0) {
			$app->enqueueMessage('geen rijen gevonden');
			return false;
		}
		$app->enqueueMessage('aantal import rijen gevonden: ' . $count);

		self::verwijderJaar($jaar);
		$app->enqueueMessage("Vorige kampen van het jaar '$jaar' zijn verwijderd.");

		self::updateKampen($rows,  $jaar);
		$app->enqueueMessage("Er zijn nu $count nieuwe kampen ingelezen");
		return true;
	}

	private function updateKampen($rows, $jaar) {
		foreach ($rows as $kamp) {
			$table = self::getTable();
			$kamp->hitsite = strtolower($kamp->plaatsNaam) . '-'. $jaar;
			$kamp->startDatumTijd = $kamp->startDatumTijd->format('Y-m-d H:i:s');
			$kamp->eindDatumTijd = $kamp->eindDatumTijd->format('Y-m-d H:i:s');
			$table->bind($kamp);
			$table->store($kamp);
		}
	}

	private function verwijderJaar($jaar) {
		$db = JFactory :: getDbo();
		$query = "DELETE c FROM #__kampinfo_hitcamp c "
				. "JOIN #__kampinfo_hitsite s ON c.hitsite=s.code "
						. "AND s.jaar = ". ($db->getEscaped($jaar));

		$db->setQuery($query);
		$db->query();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
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
			$query = "UPDATE #__kampinfo_hitcamp c, #__kampinfo_hitsite s SET"
					. "	 c.aantalDeelnemers = " .	(int)($db->getEscaped($aantalDeelnemers))
					. ", c.gereserveerd = " .		(int)($db->getEscaped($gereserveerd))
					. ", c.aantalSubgroepen = " .	(int)($db->getEscaped($aantalSubgroepen))
					. " WHERE "
					. " c.hitsite = s.code AND s.jaar = ". ($db->getEscaped($jaar))
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
	
	private function updateDeelnemergegevens($rows, $jaar) {
		$db = JFactory :: getDbo();
		
		$maxDeelnemernummer = $this->bepaalHoogsteDeelnemernummerOpDitMoment($jaar);
		
		$count = 0;
		$moetInserten = $maxDeelnemernummer == null;
		foreach ($rows as $deelnemer) {
			if ($moetInserten) {
				$formulier = array();
				preg_match("/HIT (\w+) (.*)/", $deelnemer->formulier, $formulier);
				
				$db = JFactory :: getDbo();
				$query = $db->getQuery(true);
				$query->insert('#__kampinfo_deelnemers');
				$query->set(
						'jaar = '. (int)($db->getEscaped($jaar)) .
						', dlnnr = '. (int)($db->getEscaped($deelnemer->dlnnr)) .
						', herkomst = '. $db->quote($db->getEscaped($deelnemer->plaats .', '. $deelnemer->land)) .
						', leeftijd = '. (int)($db->getEscaped($deelnemer->leeftijd)) .
						', geslacht = '. $db->quote($db->getEscaped($deelnemer->geslacht)) .
						', datumInschrijving = '. $db->quote($deelnemer->datumInschrijving->format('Y-m-d')) .
						', hitsite = ' . $db->quote($db->getEscaped(strtolower($formulier[1]).'-'.$jaar)) .
						', hitcamp = ' . $db->quote($db->getEscaped($formulier[2]))
				);
				$db->setQuery($query);
				$db->query();
				$count++;
			}
			$moetInserten = $moetInserten || $deelnemer->dlnnr == $maxDeelnemernummer;
		}
		return $count;
	}

	private function bepaalHoogsteDeelnemernummerOpDitMoment($jaar) {
		$db = JFactory :: getDbo();
		$query = $db->getQuery(true);
		$query->select('max(dlnnr) as laatste');
		$query->from('#__kampinfo_deelnemers');
		$query->where('(jaar = ' . (int)($db->getEscaped($jaar)) . ')');
		$db->setQuery($query);
		
		$row = $db->loadObjectList();
		
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}
		if ($row != null || count($row) != 0) {
			return $row[0]->laatste;
		}
		return null;
	}

	protected function getUploadedFile($fieldname)
	{
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

	public function getForm($data = array(), $loadData = true)
	{
		return self::loadForm(
				'com_kampinfo.import'
				,	'import'
				,	array('control' => 'jform', 'load_data' => $loadData));
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_kampinfo.import.data', array());
		if (empty($data))
		{
			$data = self::getItem();
		}
		return $data;
	}

	public function getTable($type = 'HitCamp', $prefix = 'KampInfoTable', $config = array ()) {
		return JTable :: getInstance($type, $prefix, $config);
	}

	private function getKampgegevensMapping() {
		$mapping = array(
		  'deelnemersnummer'												=> new GewoonVeld('deelnemersnummer')
				, 'HIT-Kamp in HIT-Plaats'											=> new GewoonVeld('plaatsNaam') // icm jaar -> hitsite
				, 'HIT-Kamp naam'													=> new GewoonVeld('naam')
				//, 'HIT-Kamp Contactpersoon voor helpdesk'							=> new IgnoredVeld()
				//, 'HIT-Kamp Contactpersoon Emailadres voor Helpdesk'				=> new IgnoredVeld()
				//, 'HIT-Kamp Contactpersoon Telefoonnummer voor Helpdesk'			=> new IgnoredVeld()
				//, 'HIT-Kamp doelstelling'											=> new IgnoredVeld()
				, 'HIT-Kamp Activiteitengebieden: Uitdagende Scoutingtechnieken'	=> new ActiviteitengebiedVeld('uitdagend')
				, 'HIT-Kamp Activiteitengebieden: Expressie'						=> new ActiviteitengebiedVeld('expressie')
				, 'HIT-Kamp Activiteitengebieden: Sport en Spel'					=> new ActiviteitengebiedVeld('sportenspel')
				, 'HIT-Kamp Activiteitengebieden: Buitenleven'						=> new ActiviteitengebiedVeld('buitenleven')
				, 'HIT-Kamp Activiteitengebieden: Identiteit'						=> new ActiviteitengebiedVeld('identiteit')
				, 'HIT-Kamp Activiteitengebieden: Internationaal'					=> new ActiviteitengebiedVeld('internationaal')
				, 'HIT-Kamp Activiteitengebieden: Samenleving'						=> new ActiviteitengebiedVeld('samenleving')
				, 'HIT-Kamp Activiteitengebieden: Veilig en Gezond'					=> new ActiviteitengebiedVeld('veiligengezond')
				, 'HIT-Kamp titeltekst'												=> new GewoonVeld('titeltekst')
				//, 'HIT-Kamp Couranttekst'											=> new GewoonVeld('courantTekst')
				, 'HIT-Kamp Startdatum'												=> new DatumVeld('startDatumTijd')
				, 'HIT-Kamp Starttijd'												=> new TijdVeld('startDatumTijd')
				, 'HIT-Kamp Einddatum'												=> new DatumVeld('eindDatumTijd')
				, 'HIT-Kamp Eindtijd'												=> new TijdVeld('eindDatumTijd')
				, 'Deelnamekosten'													=> new GewoonVeld('deelnamekosten')
				, 'Leeftijd minimaal'												=> new GewoonVeld('minimumLeeftijd')
				, 'Leeftijd maximaal'												=> new GewoonVeld('maximumLeeftijd')
				, 'Subgroepsamenstelling van'										=> new GewoonVeld('subgroepsamenstellingMinimum')
				, 'Subgroepsamenstelling tot en met'								=> new GewoonVeld('subgroepsamenstellingMaximum')
				, 'Subgroep extra'													=> new GewoonVeld('subgroepsamenstellingExtra')
				, 'De HIT Icoontjes: Staand kamp'											=> new IcoonVeld('staand')
				, 'De HIT Icoontjes: Trekken per fiets'										=> new IcoonVeld('fiets')
				, 'De HIT Icoontjes: Trekken met rugzak'									=> new IcoonVeld('hike')
				, 'De HIT Icoontjes: Trekken per kano'										=> new IcoonVeld('kano')
				, 'De HIT Icoontjes: Trekkend per boot'										=> new IcoonVeld('zeilboot')
				, 'De HIT Icoontjes: Lopen zonder rugzak'									=> new IcoonVeld('geenrugz')
				, 'De HIT Icoontjes: Lopen met een ander voorwerp'							=> new IcoonVeld('hikevr')
				, 'De HIT Icoontjes: Inschrijven per persoon'								=> new IcoonVeld('0pers')
				, 'De HIT Icoontjes: Inschrijven per groep'									=> new IcoonVeld('groepje')
				, 'De HIT Icoontjes: Overnachten in een zelfmeegenomen tent'				=> new IcoonVeld('tent')
				, 'De HIT Icoontjes: Overnachten in een frietbuil'							=> new IcoonVeld('friet')
				, 'De HIT Icoontjes: Overnachten zonder tent'								=> new IcoonVeld('nacht')
				, 'De HIT Icoontjes: Overnachten in tenten verzorgd door staf'				=> new IcoonVeld('tent_opgezet')
				, 'De HIT Icoontjes: Overnachten in gebouw'									=> new IcoonVeld('gebouw')
				, 'De HIT Icoontjes: Totale afstand is 0 km'								=> new IcoonVeld('0km')
				, 'De HIT Icoontjes: Totale afstand is 5 km'								=> new IcoonVeld('5km')
				, 'De HIT Icoontjes: Totale afstand is 15 km'								=> new IcoonVeld('15km')
				, 'De HIT Icoontjes: Totale afstand is 20 km'								=> new IcoonVeld('20km')
				, 'De HIT Icoontjes: Totale afstand is 25 km'								=> new IcoonVeld('25km')
				, 'De HIT Icoontjes: Totale afstand is 30 km'								=> new IcoonVeld('30km')
				, 'De HIT Icoontjes: Totale afstand is 35 km'								=> new IcoonVeld('35km')
				, 'De HIT Icoontjes: Totale afstand is 40 km'								=> new IcoonVeld('40km')
				, 'De HIT Icoontjes: Totale afstand is 45 km'								=> new IcoonVeld('45km')
				, 'De HIT Icoontjes: Totale afstand is 50 km'								=> new IcoonVeld('50km')
				, 'De HIT Icoontjes: Totale afstand is 55 km'								=> new IcoonVeld('55km')
				, 'De HIT Icoontjes: Totale afstand is 60 km'								=> new IcoonVeld('60km')
				, 'De HIT Icoontjes: Totale afstand is 65 km'								=> new IcoonVeld('65km')
				, 'De HIT Icoontjes: Totale afstand is 70 km'								=> new IcoonVeld('70km')
				, 'De HIT Icoontjes: Totale afstand is 75 km'								=> new IcoonVeld('75km')
				, 'De HIT Icoontjes: Totale afstand is 80 km'								=> new IcoonVeld('80km')
				, 'De HIT Icoontjes: Totale afstand is 85 km'								=> new IcoonVeld('85km')
				, 'De HIT Icoontjes: Totale afstand is 90 km'								=> new IcoonVeld('90km')
				, 'De HIT Icoontjes: Totale afstand is 100 km'								=> new IcoonVeld('100km')
				, 'De HIT Icoontjes: Totale afstand is 120 km'								=> new IcoonVeld('120km')
				, 'De HIT Icoontjes: Totale afstand is 150 km'								=> new IcoonVeld('150km')
				, 'De HIT Icoontjes: Koken op houtvuur zonder pannen'						=> new IcoonVeld('vuur')
				, 'De HIT Icoontjes: Koken op houtvuur met pannen'							=> new IcoonVeld('opvuur')
				, 'De HIT Icoontjes: Koken op gas met pannen'								=> new IcoonVeld('gas')
				, 'De HIT Icoontjes: Gekookt door de staf'									=> new IcoonVeld('stafkookt')
				, 'De HIT Icoontjes: Kennis van kaart en kompas op eenvoudig niveau'		=> new IcoonVeld('k_ks')
				, 'De HIT Icoontjes: Kennis van kaart en kompas op gevorderd niveau'		=> new IcoonVeld('k_kv')
				, 'De HIT Icoontjes: Kennis van kaart en kompas op specialistisch nivea'	=> new IcoonVeld('k_kgv')
				, 'De HIT Icoontjes: Activiteit waarmee een insigne kan worden behaald'		=> new IcoonVeld('insigne')
				, 'De HIT Icoontjes: Zwemdiploma verplicht'									=> new IcoonVeld('zwem')
				, 'De HIT Icoontjes: Mobieltje meenemen'									=> new IcoonVeld('mobieltje')
				, 'De HIT Icoontjes: Mobieltjes zijn verboden'								=> new IcoonVeld('geenmobieltje')
				, 'De HIT Icoontjes: Geschikt voor minder validen (rolstoel)'				=> new IcoonVeld('rolstoel')
				, 'De HIT Icoontjes: Vraagteken Mysterie elementen'							=> new IcoonVeld('vraagt')
				, 'De HIT Icoontjes: Buitenland - ID kaart of paspoort verplicht'			=> new IcoonVeld('buitenland')
				, 'De HIT Icoontjes: Trekkend per auto'										=> new IcoonVeld('auto')
				, 'HIT-Kamp websiteadres'											=> new GewoonVeld('websiteAdres')
				, 'HIT-Kamp websitetekst'											=> new GewoonVeld('websiteTekst')
				, 'Webadres naar foto1'												=> new GewoonVeld('webadresFoto1')
				, 'Webadres naar foto2'												=> new GewoonVeld('webadresFoto2')
				, 'Webadres naar foto3 of naar 1 Youtube filmpje'					=> new GewoonVeld('webadresFoto3')
				, 'HIT-Kamp contacttelefoonnummer voor website'						=> new GewoonVeld('websiteContactTelefoonnummer')
				, 'HIT-Kamp contactemailadres voor website  (HIT mailadres)'		=> new GewoonVeld('websiteContactEmailadres')
				, 'HIT-Kamp contactpersoon voor website'							=> new GewoonVeld('websiteContactpersoon')
				, 'Minimaal aantal deelnemers'										=> new GewoonVeld('minimumAantalDeelnemers')
				, 'Maximum aantal deelnemers'										=> new GewoonVeld('maximumAantalDeelnemers')
				, 'Maximum aantal subgroepjes'										=> new GewoonVeld('maximumAantalSubgroepjes')
				, 'Maximum aantal uit 1 Scoutinggroep'								=> new GewoonVeld('maximumAantalUitEenGroep')
				, 'Aantal dagen dat een deelnemer te jong mag zijn (standaard 90 dagen)'	=> new GewoonVeld('margeAantalDagenTeJong')
				, 'Aantal dagen dat een deelnemer te oud mag zijn (standaard 90 dagen)'		=> new GewoonVeld('margeAantalDagenTeOud')
				, 'Reden afwijking 90 dagen uitzondering'									=> new GewoonVeld('redenAfwijkingMarge')
				, 'Welke doelgroep mag alleen inschrijven voor deze HIT. (optioneel): Bevers 5-7 jaar'				=> new DoelgroepVeld('bevers')
				, 'Welke doelgroep mag alleen inschrijven voor deze HIT. (optioneel): Welpen 7-11 jaar'				=> new DoelgroepVeld('welpen')
				, 'Welke doelgroep mag alleen inschrijven voor deze HIT. (optioneel): Scouts 11-15 jaar'			=> new DoelgroepVeld('scouts')
				, 'Welke doelgroep mag alleen inschrijven voor deze HIT. (optioneel): Explorers 15-18 jaar'			=> new DoelgroepVeld('explorers')
				, 'Welke doelgroep mag alleen inschrijven voor deze HIT. (optioneel): Roverscouts 18 t/m 21 jaar'	=> new DoelgroepVeld('roverscouts')
				, 'Welke doelgroep mag alleen inschrijven voor deze HIT. (optioneel): Plusscouts 21+'				=> new DoelgroepVeld('plusscouts')
				, 'Welke doelgroep mag alleen inschrijven voor deze HIT. (optioneel): Volwassenen (ndlg)'			=> new DoelgroepVeld('ndlg')
				//, 'Mogen er deelnemers in een groepje te jong of te oud zijn.: Ja, deelnemers mogen 1 jaar te jong zijn,  max. aantal in een groepje:'			=> new IgnoredVeld()
				//, 'Mogen er deelnemers in een groepje te jong of te oud zijn. Reden: Ja, deelnemers mogen 1 jaar te jong zijn,  max. aantal in een groepje:'	=> new IgnoredVeld()
				//, 'Mogen er deelnemers in een groepje te jong of te oud zijn.: Ja, deelnemers mogen 1 jaar te oud zijn, max. aantal  in een groepje:'			=> new IgnoredVeld()
				//, 'Mogen er deelnemers in een groepje te jong of te oud zijn. Reden: Ja, deelnemers mogen 1 jaar te oud zijn, max. aantal  in een groepje:'		=> new IgnoredVeld()
				//, 'Mogen er deelnemers in een groepje te jong of te oud zijn.: Nee, geen leeftijdsuitzonderingen'												=> new IgnoredVeld()
				//, 'Overschrijding aantal deelnemers'								=> new GewoonVeld('overschrijdingAantalDeelnemers')
				//, 'Opmerkingen voor Helpdesk:'										=> new IgnoredVeld()
				, 'Akkoord HIT-kamp'												=> new GewoonVeld('akkoordHitKamp')
				, 'Akkoord HIT-plaats'												=> new GewoonVeld('akkoordHitPlaats')
				, 'Shantiformuliernummer'											=> new GewoonVeld('shantiFormuliernummer')
		);
		return $mapping;
	}

	private function getInschrijvingenMapping() {
		$mapping = array(
				'Locatie' => new IgnoredVeld()
				, 'Formuliernummer' => new GewoonVeld('shantiFormuliernummer')
				, 'Aantal dln\'s' => new GewoonVeld('aantalDeelnemers')
				, 'Gereserveerd' => new GewoonVeld('gereserveerd')
				, 'Subgroepen' => new GewoonVeld('aantalSubgroepen')
		);
		return $mapping;
	}

	private function getDeelnemergegevensMapping($jaar) {
		$mapping = array(
				'Dln.nr.' => new GewoonVeld('dlnnr')
	 		, 'Lid plaats' => new GewoonVeld('plaats')
	 		, 'Land' => new GewoonVeld('land')
	 		, 'Lid geboortedatum' => new LeeftijdVeld('leeftijd', $jaar)
	 		, 'Lid geslacht' => new GeslachtVeld('geslacht')
	 		, 'Datum inschrijving' => new DatumVeld('datumInschrijving')
	 		, 'Formulier' => new GewoonVeld('formulier')
		);
		return $mapping;
	}

}
