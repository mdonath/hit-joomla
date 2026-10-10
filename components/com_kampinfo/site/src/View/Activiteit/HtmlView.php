<?php

namespace HITScoutingNL\Component\KampInfo\Site\View\Activiteit;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

use  HITScoutingNL\Component\KampInfo\Site\Model\ActiviteitModel;


/**
 * HTML View class voor het tonen van een activiteit.
 */
class HtmlView extends BaseHtmlView {

    public $activiteit;

    public function display($tpl = null) {
        /** @var ActiviteitModel $model */
        $model      = $this->getModel();
        /** @var CMSApplication $app */
        $app        = Factory::getApplication();
        $document   = $app->getDocument();

        $this->activiteit = $model->getActiviteit();

        $document->getWebAssetManager()->useStyle('com_kampinfo.style.activiteit');

        $document->setTitle($this->activiteit->naam . ' in ' . $this->activiteit->plaats);

        parent::display($tpl);
    }

}