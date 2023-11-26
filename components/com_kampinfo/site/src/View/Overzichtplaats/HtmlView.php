<?php

namespace HITScoutingNL\Component\KampInfo\Site\View\Overzichtplaats;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;


/**
 * HTML View class voor het overzicht van alle activiteiten van een HIT plaats.
 */
class HtmlView extends BaseHtmlView {

    public function display($tpl = null) {
        $this->plaats = $this->get('Plaats');

        $wa = Factory::getApplication()->getDocument()->getWebAssetManager();
        $wa->registerAndUseStyle('com_kampinfo-overzicht', 'com_kampinfo/overzicht.css');

        return parent::display($tpl);
    }

}
