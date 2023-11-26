<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Table\Table;
use Joomla\Database\ParameterType;
use Joomla\Registry\Registry;

class DownloadlogModel extends ListModel {

    public function __construct($config = []) {
        if (empty ($config['filter_fields'])) {
            $config['filter_fields'] = [
                'id',
                'jaar',
                'soort',
                'bijgewerktOp'
            ];
        }

        parent::__construct($config);
    }

    protected function populateState($ordering = 'volgorde', $direction = 'asc') {
        // for sorting
        parent::populateState('bijgewerktOp', 'desc');

        // for filtering
        $state = $this->getUserStateFromRequest($this->context . '.filter.jaar', 'filter_jaar', '', 'string');
        $this->setState('filter.jaar', $state);
    }

    protected function getListQuery() {
        $db = Factory::getDBO();
        $query = $db->getQuery(true)
            -> select('d.*')
            -> from($db->quoteName('#__kampinfo_downloads', 'd'))
            -> join(
                'INNER',
                $db->quoteName('#__kampinfo_hitproject', 'p'),
                $db->quoteName('d.jaar') .' = '. $db->quoteName('p.jaar')
            );

        $filterJaar = $this->getState('filter.jaar');
        if (!empty($filterJaar)) {
            $query
                -> where('p.id = :filterJaar')
                -> bind(':filterJaar', $filterJaar, ParameterType::INTEGER)
            ;
        }

        // ordering clause
        $listOrder = $this->state->get('list.ordering', 'bijgewerktOp');
        $listDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->escape($listOrder) . ' ' . $db->escape($listDirn));

        return $query;
    }

}
