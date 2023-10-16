<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Table\Table;
use Joomla\Database\ParameterType;
use Joomla\Registry\Registry;

class SitesModel extends ListModel {

    public function __construct($config = []) {
        if (empty ($config['filter_fields'])) {
            $config['filter_fields'] = [
                'jaar', 'p.jaar',
                'id', 's.id',
                'naam', 's.naam',
                'published', 's.published'
            ];
        }

        parent::__construct($config);
    }

    protected function populateState($ordering = 'p.jaar', $direction = 'desc') {
        $this->setState('params', ComponentHelper::getParams('com_kampinfo'));

        // $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        // $this->setState('filter.search', $search);
        
        // $jaar = $this->getUserStateFromRequest($this->context . '.filter.jaar', 'filter_jaar', '', 'string');
        // if ($state === '') {
        // 	// gebruik huidige actieve jaar
        // 	$state = ComponentHelper::getParams('com_kampinfo')->get('huidigeActieveJaar');
        // 	// update filter op het scherm
        // 	$app->setUserState($this->context . '.filter.jaar', $state);
        // }
        // $this->setState('filter.jaar', $jaar);

        // $state = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '', 'string');
        // $this->setState('filter.published', $state);
        
        parent::populateState($ordering, $direction);
    }

    protected function getStoreId($id = '') {
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.published');
        $id .= ':' . $this->getState('filter.jaar');

        return parent::getStoreId($id);
    }

    protected function getListQuery() {
        $db = Factory::getDBO();

        $query = $db->getQuery(true);
        $query->select('s.id,s.hitproject_id,s.naam,s.published,s.akkoordHitPlaats,s.hitCourantTekst,s.contactPersoonNaam,s.contactPersoonEmail,s.contactPersoonTelefoon,s.projectcode');
        $query->from('#__kampinfo_hitsite s');

        $query->select('p.jaar as jaar');
        $query->join('LEFT', '#__kampinfo_hitproject AS p ON s.hitproject_id=p.id');

        $filterSearch = $this->getState('filter.search');
        if (!empty ($filterSearch)) {
            $filterSearch = '%' . $filterSearch . '%';
            $query
                -> where('(s.naam LIKE :naam)')
                -> bind(':naam', $filterSearch);
        }

        $filterJaar = $this->getState('filter.jaar');
        if (!empty ($filterJaar) and ($filterJaar != "-1")) {
            $filterJaar = (int) $filterJaar;
            $query
                -> where('(p.id = :jaar)')
                -> bind(':jaar', $filterJaar, ParameterType::INTEGER);
        }

        $filterPublished = $this->getState('filter.published');
        if (is_numeric($filterPublished)) {
            $filterPublished = (int) $filterPublished;
            $query
                -> where('(s.published = :published)')
                -> bind(':published', $filterPublished, ParameterType::INTEGER);
        } elseif ($filterPublished === '') {
            $query->where('(s.published IN (0,1))');
        }

        $listOrder = $this->state->get('list.ordering', 'p.jaar');
        $listDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->escape($listOrder) . ' ' . $db->escape($listDirn));

        return $query;
    }

}
