<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Table\Table;
use Joomla\Database\ParameterType;
use Joomla\Registry\Registry;

class ProjectsModel extends ListModel {

    public function __construct($config = array ()) {
        if (empty ($config['filter_fields'])) {
            $config['filter_fields'] = array (
                'jaar', 'p.jaar',
                'id', 'p.id'
            );
        }

        parent::__construct($config);
    }

    protected function populateState($ordering = 'p.jaar', $direction = 'desc') {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        
        $jaar = $this->getUserStateFromRequest($this->context . '.filter.jaar', 'filter_jaar', '', 'string');
        $this->setState('filter.jaar', $jaar);
        
        parent::populateState($ordering, $direction);
    }

    protected function getStoreId($id = '') {
        $id .= ':' . $this->getState('filter.search');

        return parent::getStoreId($id);
    }

    protected function getListQuery() {
        $db = Factory::getDBO();
        $query = $db->getQuery(true);
        $query->select('p.*');
        $query->from('#__kampinfo_hitproject p');

        $jaar = $this->getState('filter.search');
        if (is_numeric($jaar)) {
            $jaar = (int) $jaar;
            $query
                -> where('p.jaar = :jaar')
                -> bind(':jaar', $jaar, ParameterType::INTEGER);
        }
        
        // ordering clause
        $listOrder = $this->state->get('list.ordering', 'p.jaar');
        $listDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->escape($listOrder) . ' ' . $db->escape($listDirn));

        return $query;
    }

}
