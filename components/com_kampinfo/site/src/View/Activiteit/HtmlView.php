<?php

namespace HITScoutingNL\Component\KampInfo\Site\View\Activiteit;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;


/**
 * HTML View class voor het tonen van een activiteit.
 */
class HtmlView extends BaseHtmlView {

    protected $activiteit;

    public function display($tpl = null) {
        $model      = $this->getModel();
        $app        = Factory::getApplication();
        $document   = $app->getDocument();

        $this->activiteit = $model->getActiviteit();

        $document->getWebAssetManager()->useStyle('com_kampinfo.style.activiteit');

        $document->setTitle($this->activiteit->naam . ' in ' . $this->activiteit->plaats);

        return parent::display($tpl);
    }

}