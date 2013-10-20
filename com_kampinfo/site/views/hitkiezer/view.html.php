<?php defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class voor de HIT Kiezer.
*/
class KampInfoViewHitkiezer extends JView {

	// Overwriting JView display method
	function display($tpl = null) {
		// Assign data to the view
		$this->project = $this->get('Project');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError :: raiseError(500, implode('<br />', $errors));
			return false;
		}

		$params = &JComponentHelper::getParams('com_kampinfo');
		$iconFolderLarge = $params->get('iconFolderLarge');
		$iconExtension = $params->get('iconExtension');

		$document =& JFactory :: getDocument();
		JHTML::stylesheet("hitkiezer.css", "media/com_kampinfo/css/");

		$document->addScript("https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js");
		$document->addScript("media/com_kampinfo/js/jquery.cookies.2.2.0.min.js");

		self::integerifyFields($this->project);
		$json = json_encode($this->project);
		$document->addScriptDeclaration('var hit = '.$json);

		$document->addScript("media/com_kampinfo/js/common.js");
		$document->addScript("media/com_kampinfo/js/hitkiezer.js");

		$document->addScriptDeclaration('kampinfoConfig.iconFolderLarge = "'.JURI::root().$iconFolderLarge . '"; kampinfoConfig.iconExtension="'. $iconExtension .'"');

		// Display the view
		parent :: display($tpl);
	}

	/**
	 * Maakt van velden (waar dat van nodig is) een integer om juiste json-encoding te krijgen. Anders worden het strings en dan gaat het mis met vergelijkingen.
	 * @param unknown $project
	 */
	private static function integerifyFields($project) {
		// Alle velden in een array zodat ze in een loopje omgezet kunnen worden
		$kampFields = explode(',', 'shantiFormuliernummer,minimumLeeftijd,maximumLeeftijd,deelnamekosten,minimumAantalDeelnemers,maximumAantalDeelnemers,aantalDeelnemers,gereserveerd,subgroepsamenstellingMinimum,margeAantalDagenTeJong,margeAantalDagenTeOud');

		$project->jaar = intval($project->jaar);
		foreach ($project->hitPlaatsen as $plaats) {
			foreach ($plaats->kampen as $kamp) {
				foreach ($kampFields as $field) {
					$kamp->$field = intval($kamp->$field);
				}
			}
		}

	}
}