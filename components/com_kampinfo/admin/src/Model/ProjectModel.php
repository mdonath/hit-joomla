<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Date\Date;
use Joomla\CMS\MVC\Model\AdminModel;


class ProjectModel extends AdminModel {

    /**
     * Method to get the group form.
     *
     * @param   array    $data      Data for the form.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     *
     * @return  \Joomla\CMS\Form\Form|bool  A Form object on success, false on failure.
     */
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
        /** @var CMSApplication $app */
        $app = Factory::getApplication();
        $data = $app->getUserState('com_kampinfo.edit.project.data', []);

        if (empty($data)) {
            $data = $this->getItem();
        }

        $this->preprocessData('com_kampinfo.project', $data);

        return $data;
    }

}
