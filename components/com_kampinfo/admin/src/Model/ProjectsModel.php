<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Table\Table;
use Joomla\Database\ParameterType;
use Joomla\Registry\Registry;


class ProjectsModel extends ListModel {

    public function __construct($config = [], ?MVCFactoryInterface $factory = null) {
        if (empty ($config['filter_fields'])) {
            $config['filter_fields'] = array (
                'jaar', 'p.jaar',
                'id', 'p.id'
            );
        }

        parent::__construct($config, $factory);
    }

    protected function populateState($ordering = 'p.jaar', $direction = 'desc') {
        // Filter op jaar/project
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        
        parent::populateState($ordering, $direction);
    }

    protected function getStoreId($id = '') {
        $id .= ':' . $this->getState('filter.search');

        return parent::getStoreId($id);
    }

    protected function getListQuery() {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select('p.*')
            ->from($db->quoteName('#__kampinfo_hitproject', 'p'));

        // Filter op jaar
        $jaar = $this->getState('filter.search');
        if (is_numeric($jaar)) {
            $jaar = (int) $jaar;
            $query
                ->where($db->quoteName('p.jaar') . ' = :jaar')
                ->bind(':jaar', $jaar, ParameterType::INTEGER);
        }
        
        // Sortering
        $orderCol = $this->state->get('list.ordering', 'p.jaar');
        $orderDirn = $this->state->get('list.direction', 'DESC');
        $query->order($db->quoteName($orderCol) . ' ' . $db->escape($orderDirn));

        return $query;
    }

}
