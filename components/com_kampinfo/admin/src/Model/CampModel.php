<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;


class CampModel extends AdminModel {

    public function getForm($data = [], $loadData = true) {
        $form = $this->loadForm(
            'com_kampinfo.camp',
            'camp',                  // admin/forms/camp.xml
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

    protected function canEditState($record) {
        return $this->getCurrentUser()->authorise('hitcamp.edit.state', 'com_kampinfo.camp.' . (int) $record->id);
    }

    protected function canEdit($record) {
        return $this->getCurrentUser()->authorise('hitcamp.edit', 'com_kampinfo.camp.' . (int) $record->id);
    }

    protected function canEditParent($record) {
        return $this->getCurrentUser()->authorise('hitsite.edit', 'com_kampinfo.site.' . (int) $record->hitsite_id);
    }

    protected function loadFormData() {
        $data = Factory::getApplication()->getUserState('com_kampinfo.edit.camp.data', []);

        if (empty($data)) {
            $data = $this->getItem();
        }

        $this->preprocessData('com_kampinfo.camp', $data);

        return $data;
    }

    public function akkoordPlaats(&$pks, $value = 1) {
        $table = $this->getTable();
        $pks   = (array) $pks;

        // Access checks.
        foreach ($pks as $i => $pk) {
            if ($table->load($pk)) {
                if (!$this->canEditParent($table)) {
                    // Prune items that you can't change.
                    unset($pks[$i]);
                    Factory::getApplication()->enqueueMessage(Text::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'), 'error');
                }
            }
        }

        // Attempt to change the state of the records.
        if (!$table->akkoordPlaats($pks, $value)) {
            $this->setError($table->getError());
            return false;
        }

        return true;
    }

    public function akkoordKamp(&$pks, $value = 1) {
        $table = $this->getTable();
        $pks   = (array) $pks;

        // Access checks.
        foreach ($pks as $i => $pk) {
            if ($table->load($pk)) {
                if (!$this->canEdit($table)) {
                    // Prune items that you can't change.
                    unset($pks[$i]);
                    Factory::getApplication()->enqueueMessage(Text::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'), 'error');
                }
            }
        }

        // Attempt to change the state of the records.
        if (!$table->akkoordKamp($pks, $value)) {
            $this->setError($table->getError());
            return false;
        }

        return true;
    }

}
