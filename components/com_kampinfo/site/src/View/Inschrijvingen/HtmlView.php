<?php

namespace HITScoutingNL\Component\KampInfo\Site\View\Inschrijvingen;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;


/**
 * HTML View class voor het overzicht van alle inschrijvingen.
 */
class HtmlView extends BaseHtmlView {

    protected $project;

    public function display($tpl = null) {
        $model      = $this->getModel();
        $app        = Factory::getApplication();
        $document   = $app->getDocument();

        $this->project = $model->getProject();

        $document->getWebAssetManager()->useStyle('com_kampinfo.style.inschrijvingen');

        return parent::display($tpl);
    }

}