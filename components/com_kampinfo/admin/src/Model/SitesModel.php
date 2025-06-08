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


class SitesModel extends ListModel {

    public function __construct($config = [], ?MVCFactoryInterface $factory = null) {
        if (empty ($config['filter_fields'])) {
            $config['filter_fields'] = [
                'jaar', 'p.jaar',
                'id', 's.id',
                'naam', 's.naam',
                'published', 's.published'
            ];
        }

        parent::__construct($config, $factory);
    }

    protected function populateState($ordering = 'p.jaar', $direction = 'desc') {
        $this->setState('params', ComponentHelper::getParams('com_kampinfo'));

        // Filter op naam van plaats
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

        // Filter op published
        $state = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '', 'string');
        $this->setState('filter.published', $state);

        // Sortering
        parent::populateState($ordering, $direction);
    }

    protected function getStoreId($id = '') {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.published');
        $id .= ':' . $this->getState('filter.jaar');

        return parent::getStoreId($id);
    }

    protected function getListQuery() {
        $db = $this->getDatabase();

        $query = $db->getQuery(true)
            ->select([
                $db->quoteName('s.id'),
                $db->quoteName('s.hitproject_id'),
                $db->quoteName('s.naam'),
                $db->quoteName('s.published'),
                $db->quoteName('s.akkoordHitPlaats'),
                $db->quoteName('s.hitCourantTekst'),
                $db->quoteName('s.contactPersoonNaam'),
                $db->quoteName('s.contactPersoonEmail'),
                $db->quoteName('s.contactPersoonTelefoon'),
                $db->quoteName('s.projectcode'),
            ])
            ->from($db->quoteName('#__kampinfo_hitsite', 's'))
            ->select($db->quoteName('p.jaar', 'jaar'))
            ->join('LEFT',
                $db->quoteName('#__kampinfo_hitproject', 'p'),
                $db->quoteName('s.hitproject_id') .' = '. $db->quoteName('p.id'))
        ;

        $filterSearch = $this->getState('filter.search');
        if (!empty ($filterSearch)) {
            $filterSearch = '%' . $filterSearch . '%';
            $query
                ->where($db->quoteName('s.naam') . ' LIKE :naam')
                ->bind(':naam', $filterSearch);
        }

        $filterJaar = $this->getState('filter.jaar');
        if (is_numeric($filterJaar) && ($filterJaar !== '-1')) {
            $filterJaar = (int) $filterJaar;
            $query
                ->where($db->quoteName('p.id') . ' = :jaar')
                ->bind(':jaar', $filterJaar, ParameterType::INTEGER);
        }

        $filterPublished = $this->getState('filter.published');
        if (is_numeric($filterPublished)) {
            $filterPublished = (int) $filterPublished;
            $query
                ->where($db->quoteName('s.published') . ' = :published')
                ->bind(':published', $filterPublished, ParameterType::INTEGER);
        } elseif ($filterPublished == '') {
            $query->where($db->quoteName('s.published') . ' IN (0,1)');
        }

        $orderCol = $this->state->get('list.ordering', 'p.jaar');
        $orderDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->quoteName($orderCol) . ' ' . $db->escape($orderDirn));

        return $query;
    }

}
