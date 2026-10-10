<?php

namespace HITScoutingNL\Component\KampInfoImExport\Administrator\View\Export;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\JsonView as BaseJsonView;

use HITScoutingNL\Component\KampInfoImExport\Administrator\Model\ExportModel;


class JsonView extends BaseJsonView {

    public $items;

    function display($tpl = null) {
        /** @var ExportModel $model */
        $model       = $this->getModel();
        $this->items = $model->getItems();

        $hit = new \stdClass();
        $hit->projecten = $this->items;
        $this->_output = $hit;

        parent::display($tpl);

        echo $this->getDocument()->getBuffer();
    }

}
