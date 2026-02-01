<?php

namespace HITScoutingNL\Component\KampInfo\Site\View\Overzichtplaats;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;


/**
 * HTML View class voor het overzicht van alle activiteiten van een HIT plaats.
 */
class HtmlView extends BaseHtmlView {

    protected $plaats;

    public function display($tpl = null) {
        $model      = $this->getModel();
        $app        = Factory::getApplication();
        $document   = $app->getDocument();

        $this->plaats = $model->getPlaats();

        $document->getWebAssetManager()->useStyle('com_kampinfo.style.overzicht');

        $document->setTitle('Alle activiteiten in HIT ' . $this->plaats->naam);

        return parent::display($tpl);
    }

}
