<?php

namespace HITScoutingNL\Component\KampInfo\Site\View\Inschrijvingen;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

use  HITScoutingNL\Component\KampInfo\Site\Model\InschrijvingenModel;


/**
 * HTML View class voor het overzicht van alle inschrijvingen.
 */
class HtmlView extends BaseHtmlView {

    public $project;

    public function display($tpl = null) {
        /** @var InschrijvingenModel $model */
        $model      = $this->getModel();
        /** @var CMSApplication $app */
        $app        = Factory::getApplication();
        $document   = $app->getDocument();

        $this->project = $model->getProject();

        $document->getWebAssetManager()->useStyle('com_kampinfo.style.inschrijvingen');

        parent::display($tpl);
    }

}