<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\Date\Date;
use Joomla\CMS\MVC\Model\AdminModel;


class ProjectModel extends AdminModel {

    public function getForm($data = [], $loadData = true) {
        $form = $this->loadForm(
            'com_kampinfo.project',
            'project',
            [
                'control' => 'jform',
                'load_data' => $loadData
            ]
        );

        if (empty($form)) {
            return false;
        }

        $volgendJaar = ((int) ((new Date())->format('Y', true))) + 1;
        $form->setFieldAttribute('jaar', 'default', $volgendJaar);

        return $form;
    }

    protected function loadFormData() {
        $data = Factory::getApplication()->getUserState('com_kampinfo.edit.project.data', []);

        if (empty($data)) {
            $data = $this->getItem();
        }

        $this->preprocessData('com_kampinfo.project', $data);

        return $data;
    }

}
