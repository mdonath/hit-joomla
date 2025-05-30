<?php

namespace HITScoutingNL\Component\KampInfo\Site\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Database\ParameterType;

use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;

/**
 * KampInfo Inschrijvingen Model
 */
class InschrijvingenModel extends AbstractKampInfoModel {

    public function getProject() {
        $input = Factory::getApplication()->getInput();
        $projectId = $input->getInt('hitproject_id', 0);

        if ($projectId == 0) {
            throw new GenericDataException('Project niet gevonden?!', 404);
        }

        $project = $this->getHitProject($projectId);
        $project->plaatsen = $this->getHitPlaatsen($projectId);

        foreach ($project->plaatsen as $plaats) {
            $plaats->kampen = $this->getHitKampen($plaats->id, []);
        }
        $project->laatstBijgewerktOp = $this->getLaatstBijgewerktOp($project->jaar);

        return $project;
    }

}
