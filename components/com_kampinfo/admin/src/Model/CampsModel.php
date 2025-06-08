<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Model;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Table\Table;
use Joomla\Database\ParameterType;
use Joomla\Registry\Registry;


class CampsModel extends ListModel {

    public function __construct($config = [], ?MVCFactoryInterface $factory = null) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'published',
                'naam',
                'plaats',
                'jaar',
                'gereserveerd',
                'aantalDeelnemers',
                'deelnamekosten',
                'id',
            ];
        }

        parent::__construct($config, $factory);
    }

    protected function populateState($ordering = 'p.jaar', $direction = 'desc') {
        // Filter op naam van kamp
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        
        // Filter op jaar/project
        $jaar = $this->getUserStateFromRequest($this->context . '.filter.jaar', 'filter_jaar', '', 'string');
        if ($jaar === '') {
            // gebruik huidige actieve jaar
            $jaar = ComponentHelper::getParams('com_kampinfo')->get('huidigeActieveJaar');
            // update filter op het scherm
            $app = Factory::getApplication();
            $app->setUserState($this->context . '.filter.jaar', $jaar);
        }
        $this->setState('filter.jaar', $jaar);

        // Filter op plaats
        $plaats = $this->getUserStateFromRequest($this->context . '.filter.plaats', 'filter_plaats', '', 'string');
        if ($plaats == '-1') {
            $plaats = '';
        }
        $this->setState('filter.plaats', $plaats);

        // Filter op published
        $state = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '', 'string');
        $this->setState('filter.published', $state);
        
        // Sortering
        parent::populateState($ordering, $direction);
    }

    protected function getStoreId($id = '') {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.jaar');
        $id .= ':' . $this->getState('filter.plaats');
        $id .= ':' . $this->getState('filter.published');

        return parent::getStoreId($id);
    }

    protected function getListQuery() {
        $db = $this->getDatabase();

        $query = $db->getQuery(true)
            ->select('c.*')
            ->from($db->quoteName('#__kampinfo_hitcamp', 'c'))

            ->select($db->quoteName('s.naam', 'plaats'))
            ->join('LEFT',
                $db->quoteName('#__kampinfo_hitsite', 's'),
                $db->quoteName('c.hitsite_id') .' = '. $db->quoteName('s.id')
            )
            ->select($db->quoteName('p.jaar', 'jaar'))
            ->join('LEFT',
                $db->quoteName('#__kampinfo_hitproject', 'p'),
                $db->quoteName('s.hitproject_id') .' = '. $db->quoteName('p.id')
            );

        // Filter op naam van kamp
        $filterSearch = $this->getState('filter.search');
        if (!empty ($filterSearch)) {
            $filterSearch = '%' . $filterSearch . '%';
            $query
                ->where($db->quoteName('c.naam') . ' LIKE :naam')
                ->bind(':naam', $filterSearch);
        }
        
        // Filter op jaar/project
        $filterJaar = $this->getState('filter.jaar');
        if (!empty ($filterJaar) && $filterJaar != '-1') {
            $filterJaar = (int) $filterJaar;
            $query
                ->where($db->quoteName('p.id') . ' = :jaar')
                ->bind(':jaar', $filterJaar, ParameterType::INTEGER);
        }

        // Filter  op plaats (alleen als filterPlaats en filterJaar kloppen met elkaar)
        $filterPlaats = $this->getState('filter.plaats');
        if (!empty ($filterPlaats) && ($filterPlaats != '-1')) {
            $filterPlaats = (int) $filterPlaats;
            $query
                ->where($db->quoteName('c.hitsite_id') . ' = :plaats_id')
                ->bind(':plaats_id', $filterPlaats, ParameterType::INTEGER);
        }

        // Filter op published
        $filterPublished = $this->getState('filter.published');
        if (is_numeric($filterPublished)) {
            $filterPublished = (int) $filterPublished;
            $query
                ->where($db->quoteName('c.published') . ' = :published')
                ->bind(':published', $filterPublished, ParameterType::INTEGER);
        } elseif ($filterPublished === '') {
            $query->where($db->quoteName('c.published') .' IN (0,1)');
        }

        // Sortering
        $orderCol = $this->getState('list.ordering', 'jaar');
        $orderDirn = $this->getState('list.direction', 'ASC');
        if ($orderCol === 'plaats') {
            $orderCol = 's.naam';
        }
        $query->order($db->quoteName($orderCol) . ' ' . $db->escape($orderDirn));

        return $query;
    }

}
