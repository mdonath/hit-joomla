<?php

namespace HITScoutingNL\Component\KampInfoImExport\Administrator\View\Export;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\JsonView as BaseJsonView;

class JsonView extends BaseJsonView {

    function display($tpl = null) {
        $document = Factory::getDocument();
        // $document->setMimeEncoding('application/json');

        $hit = new \stdClass();
        $hit->projecten = $this->getModel()->getItems();
        $this->_output = $hit;

        parent::display($tpl);

        echo($this->document->getBuffer());
    }

}
