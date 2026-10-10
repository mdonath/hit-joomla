<?php

namespace HITScoutingNL\Component\KampInfo\Site\View\Overzichtplaats;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

use  HITScoutingNL\Component\KampInfo\Site\Model\OverzichtplaatsModel;


/**
 * HTML View class voor het overzicht van alle activiteiten van een HIT plaats.
 */
class HtmlView extends BaseHtmlView {

    public $plaats;

    public function display($tpl = null) {
        /** @var OverzichtplaatsModel $model */
        $model      = $this->getModel();
        /** @var CMSApplication $app */
        $app        = Factory::getApplication();
        $document   = $app->getDocument();

        $this->plaats = $model->getPlaats();

        $document->getWebAssetManager()->useStyle('com_kampinfo.style.overzicht');

        $document->setTitle('Alle activiteiten in HIT ' . $this->plaats->naam);

        parent::display($tpl);
    }


}
