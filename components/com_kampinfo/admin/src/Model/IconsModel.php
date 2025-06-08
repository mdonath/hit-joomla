<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;


class IconsModel extends ListModel {

    public function __construct($config = [], ?MVCFactoryInterface $factory = null) {
        if (empty ($config['filter_fields'])) {
            $config['filter_fields'] = [
                'id',
                'volgorde',
                'soort'
            ];
        }

        parent::__construct($config, $factory);
    }

    protected function populateState($ordering = 'volgorde', $direction = 'asc') {
        // Filter op (deel van) bestandsnaam en/of uitleg
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        
        // Filter op soort
        $jaar = $this->getUserStateFromRequest($this->context . '.filter.soort', 'filter_soort', '', 'string');
        $this->setState('filter.soort', $jaar);
        
        // Sortering
        parent::populateState($ordering, $direction);
    }

    protected function getStoreId($id = '') {
        $id .= ':' . $this->getState('filter.search');

        return parent::getStoreId($id);
    }

    protected function getListQuery() {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select('i.*')
            ->from($db->quoteName('#__kampinfo_hiticon', 'i'));

        // Filter op (deel van) bestandsnaam en/of uitleg
        $filterSearch = $this->getState('filter.search');
        if (!empty ($filterSearch)) {
            $term = '%' . $filterSearch . '%';
            $query
                ->where('('. $db->quoteName('i.bestandsnaam') .' LIKE :term1) OR ('. $db->quoteName('i.tekst'). ' LIKE :term2)')
                ->bind(':term1', $term)
                ->bind(':term2', $term);
        }

        // Filter op soort
        $filterSoort = $this->getState('filter.soort');
        if (!empty ($filterSoort) && $filterSoort != '-1') {
            $query
                ->where($db->quoteName('i.soort') .' = :soort')
                ->bind(':soort', $filterSoort);
        }

        // Sortering
        $orderCol = $this->state->get('list.ordering', 'p.jaar');
        $orderDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->quoteName($orderCol) . ' ' . $db->escape($orderDirn));

        return $query;
    }

}
