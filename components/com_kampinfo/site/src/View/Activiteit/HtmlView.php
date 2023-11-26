<?php

namespace HITScoutingNL\Component\KampInfo\Site\View\Activiteit;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;


/**
 * HTML View class voor het overzicht van alle activiteiten.
 */
class HtmlView extends BaseHtmlView {

    public function display($tpl = null) {
        $this->activiteit = $this->get('Activiteit');

        $document = Factory::getApplication()->getDocument();

        $wa = $document->getWebAssetManager();
        $wa->useStyle('com_kampinfo-activiteit');

        $document->setTitle($this->activiteit->naam . ' in ' . $this->activiteit->plaats);

        return parent::display($tpl);
    }

}