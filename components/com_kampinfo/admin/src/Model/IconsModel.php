<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;


class IconsModel extends ListModel {

    public function __construct($config = []) {
        if (empty ($config['filter_fields'])) {
            $config['filter_fields'] = [
                'id',
                'volgorde',
                'soort'
            ];
        }

        parent::__construct($config);
    }

    protected function populateState($ordering = 'volgorde', $direction = 'asc') {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        
        $jaar = $this->getUserStateFromRequest($this->context . '.filter.jaar', 'filter_jaar', '', 'string');
        $this->setState('filter.soort', $jaar);
        
        parent::populateState($ordering, $direction);
    }

    protected function getStoreId($id = '') {
        $id .= ':' . $this->getState('filter.search');

        return parent::getStoreId($id);
    }

    protected function getListQuery() {
        $db = Factory::getDBO();
        $query = $db->getQuery(true);
        $query->select('i.*');
        $query->from('#__kampinfo_hiticon i');

        $filterSearch = $this->getState('filter.search');
        if (!empty ($filterSearch)) {
            $term = '%' . $filterSearch . '%';
            $query
                -> where('(i.bestandsnaam LIKE :term1) OR (i.tekst LIKE :term2)')
                -> bind(':term1', $term)
                -> bind(':term2', $term);
        }

        $filterSoort = $this->getState('filter.soort');
        if (!empty ($filterSoort) && $filterSoort != '-1') {
            $query
                -> where('(i.soort = :soort)')
                -> bind(':soort', $filterSoort);
        }

        // ordering clause
        $listOrder = $this->state->get('list.ordering', 'p.jaar');
        $listDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->escape($listOrder) . ' ' . $db->escape($listDirn));

        return $query;
    }

}
