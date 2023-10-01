<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Date\Date;
use Joomla\CMS\MVC\Model\AdminModel;


class IconModel extends AdminModel {

    public function getForm($data = [], $loadData = true) {
        $form = $this->loadForm(
            'com_kampinfo.icon',
            'icon',
            [
                'control' => 'jform',
                'load_data' => $loadData
            ]
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData() {
        $data = Factory::getApplication()->getUserState('com_kampinfo.edit.icon.data', []);

        if (empty($data)) {
            $data = $this->getItem();
        }

        $this->preprocessData('com_kampinfo.icon', $data);

        return $data;
    }

}
