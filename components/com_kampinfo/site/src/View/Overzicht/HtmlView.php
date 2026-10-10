<?php

namespace HITScoutingNL\Component\KampInfo\Site\View\Overzicht;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

use  HITScoutingNL\Component\KampInfo\Site\Model\OverzichtModel;


/**
 * HTML View class voor het overzicht van alle activiteiten.
 */
class HtmlView extends BaseHtmlView {

    public $project;

    public function display($tpl = null) {
        /** @var OverzichtModel $model */
        $model      = $this->getModel();
        /** @var CMSApplication $app */
        $app        = Factory::getApplication();
        $document   = $app->getDocument();

        $this->project = $model->getProject();

        $document->getWebAssetManager()->useStyle('com_kampinfo.style.overzicht');

        parent::display($tpl);
    }

}