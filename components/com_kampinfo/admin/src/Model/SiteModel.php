<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\Database\ParameterType;

use HITScoutingNL\Component\KampInfo\Administrator\Helper\MenuHelper;


class SiteModel extends AdminModel {

    public function getForm($data = [], $loadData = true) {
        $form = $this->loadForm(
            'com_kampinfo.site',
            'site',
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

    public function getKampen($plaats) {
        $siteId = $plaats->id;
        return $this->getVolledigeKampenVanPlaats($siteId);
    }

    private function getVolledigeKampenVanPlaats($siteId) {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select([
                'c.*',
                $db->quoteName('p.jaar'),
            ])
            ->from($db->quoteName('#__kampinfo_hitcamp', 'c'))
            ->join('LEFT',
                $db->quoteName('#__kampinfo_hitsite', 's'),
                $db->quoteName('c.hitsite_id') . ' = ' . $db->quoteName('s.id')
            )
            ->join('LEFT',
                $db->quoteName('#__kampinfo_hitproject', 'p'),
                $db->quoteName('s.hitproject_id') .' = '. $db->quoteName('p.id')
            )
            ->where($db->quoteName('c.hitsite_id') .' = :siteId')
            ->bind(':siteId', $siteId, ParameterType::INTEGER)
            ->order($db->quoteName('c.naam'));

            $db->setQuery($query);
        
        $result = $db->loadObjectList();

        // expand icons
        $iconenMap = IcoonUtil::getIconenMap($db);
        foreach ($result as $kamp) {
            $kamp->icoontjes = IcoonUtil::explodeIcoontjes($kamp, $iconenMap);
        }
        
        return $result;
    }

    protected function canEditState($record) {
        return $this->getCurrentUser()->authorise('hitsite.edit.state', 'com_kampinfo.site.' . (int) $record->id);
    }

    protected function canEdit($record) {
        return $this->getCurrentUser()->authorise('hitsite.edit', 'com_kampinfo.site.' . (int) $record->id);
    }

    protected function loadFormData() {
        $data = Factory::getApplication()->getUserState('com_kampinfo.edit.site.data', []);

        if (empty($data)) {
            $data = $this->getItem();

            if ($this->getState('site.id') == 0) {
                $actieveJaar = ComponentHelper::getParams('com_kampinfo')->get('huidigeActieveJaar');
                $data->set('hitproject_id', $actieveJaar);
            }
        }

        $this->preprocessData('com_kampinfo.site', $data);

        return $data;
    }

    public function createMenu(&$pks) {
        $app = Factory::getApplication();
        $sitemenu = $app->getMenu('site');
        $sitemenu->load();
        $db = $this->getDatabase();

        $component = ComponentHelper::getComponent('com_kampinfo');

        $helper = new MenuHelper($db);
        foreach ($pks as $siteId) {
            $helper->createPlaatsMenu($sitemenu, $component, $siteId);
        }
    }


    public function copyKampen(&$pks) {
        $table = $this->getTable();
        $pks   = (array) $pks;

        // Access checks.
        foreach ($pks as $i => $pk) {
            if ($table->load($pk)) {
                if (!$this->canEdit($table)) {
                    // Prune items that you can't change.
                    $key = $pks[$i];
                    unset($pks[$i]);
                    Factory::getApplication()->enqueueMessage(Text::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED') . ' ' . $key, 'error');
                }
            }
        }

        // Attempt to change the state of the records.
        if (\count($pks) == 0) {
            Factory::getApplication()->enqueueMessage('Er bleef niets over', 'error');
            return 0;
        }

        return $table->copyKampen($pks);
    }

    public function akkoordPlaats(&$pks, $value = 1) {
        $table = $this->getTable();
        $pks   = (array) $pks;

        // Access checks.
        foreach ($pks as $i => $pk) {
            if ($table->load($pk)) {
                if (!$this->canEdit($table)) {
                    // Prune items that you can't change.
                    $key = $pks[$i];
                    unset($pks[$i]);
                    Factory::getApplication()->enqueueMessage(Text::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED') . ' ' . $key, 'error');
                }
            }
        }

        // Attempt to change the state of the records.
        if (\count($pks) == 0) {
            return false;
        }
        if (!$table->akkoordPlaats($pks, $value)) {
            $this->setError($table->getError());
            return false;
        }

        return true;
    }

}
