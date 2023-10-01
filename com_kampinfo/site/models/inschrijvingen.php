<?php

use Joomla\CMS\Factory;

defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
include_once dirname(__FILE__) . '/kampinfomodelparent.php';

/**
 * KampInfo Inschrijvingen Model
 */
class KampInfoModelInschrijvingen extends KampInfoModelParent {

	public function getProject() {
		$input = Factory::getApplication()->input;
		$projectId = $input->getInt('hitproject_id', 0);

		$project = $this->getHitProject($projectId);
		$project->plaatsen = $this->getHitPlaatsen($projectId);

		$iconenLijst = $this->getIconenLijst();
		foreach ($project->plaatsen as $plaats) {
			$plaats->kampen = $this->getHitKampen($plaats->id, $iconenLijst);
		}
		$project->laatstBijgewerktOp = $this->getLaatstBijgewerktOp($project->jaar);
		return $project;
	}


}