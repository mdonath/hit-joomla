<?php

namespace HITScoutingNL\Component\KampInfo\Site\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Database\ParameterType;

use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;

/**
 * KampInfo Overzicht Model
 */
class OverzichtModel extends AbstractKampInfoModel {

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
