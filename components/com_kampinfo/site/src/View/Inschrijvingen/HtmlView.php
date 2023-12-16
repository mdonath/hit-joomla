<?php

namespace HITScoutingNL\Component\KampInfo\Site\View\Inschrijvingen;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;


/**
 * HTML View class voor het overzicht van alle inschrijvingen.
 */
class HtmlView extends BaseHtmlView {

    public function display($tpl = null) {
        $this->project = $this->get('Project');

        $wa = Factory::getApplication()->getDocument()->getWebAssetManager();
        $wa->useStyle('com_kampinfo-inschrijvingen');

        return parent::display($tpl);
    }

}