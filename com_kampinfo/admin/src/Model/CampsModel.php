<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;

class CampsModel extends ListModel {

	public function __construct($config = array ()) {
		if (empty ($config['filter_fields'])) {
			$config['filter_fields'] = array (
				'id',
				'jaar',
				'plaats',
				'shantiFormuliernummer',
				'naam',
				'gereserveerd',
				'aantalDeelnemers',
				'published'
			);
		}

		parent::__construct($config);
	}

	protected function populateState($ordering = 'p.jaar', $direction = 'desc') {
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$jaar = $this->getUserStateFromRequest($this->context . '.filter.jaar', 'filter_jaar', '', 'string');
		if ($state === '') {
			// gebruik huidige actieve jaar
			$state = ComponentHelper::getParams('com_kampinfo')->get('huidigeActieveJaar');
			// update filter op het scherm
			$app->setUserState($this->context . '.filter.jaar', $state);
		}
		$this->setState('filter.jaar', $jaar);

		$plaats = $this->getUserStateFromRequest($this->context . '.filter.plaats', 'filter_plaats', '', 'string');
		if ($plaats == '-1') {
			$plaats == "";
		}
		$this->setState('filter.plaats', $plaats);

		$state = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '', 'string');
		$this->setState('filter.published', $state);
		
		parent::populateState($ordering, $direction);
	}

	protected function getStoreId($id = '') {
        $id .= ':' . $this->getState('filter.search');

        return parent::getStoreId($id);
    }

	protected function getListQuery() {
		$db = Factory::getDBO();

		$query = $db->getQuery(true);
		$query->select('c.*');
		$query->from('#__kampinfo_hitcamp c');

		$query->select('s.naam as plaats');
		$query->join('LEFT', '#__kampinfo_hitsite AS s ON c.hitsite_id=s.id');

		$query->select('p.jaar as jaar');
		$query->join('LEFT', '#__kampinfo_hitproject AS p ON s.hitproject_id=p.id');

		$filterSearch = $this->getState('filter.search');
		if (!empty ($filterSearch)) {
			$query->where('(c.naam LIKE ' . $db->quote('%'.$db->escape($filterSearch).'%') . ')');
		}
		$filterJaar = $this->getState('filter.jaar');
		if (!empty ($filterJaar) and $filterJaar != "-1") {
			$query->where('(p.id = ' . (int)($db->escape($filterJaar)) . ')');
		}

		// Alleen als filterPlaats en filterJaar kloppen met elkaar
		$filterPlaats = $this->getState('filter.plaats');
		if (!empty ($filterPlaats) and ($filterPlaats != "-1")) {
			$query->where('(c.hitsite_id = ' . (int)($db->escape($filterPlaats)) . ')');
		}

		$filterPublished = $this->getState('filter.published');
		if (is_numeric($filterPublished)) {
			$query->where('(c.published = '. (int)($db->escape($filterPublished)) .')');
		} elseif ($filterPublished === '') {
			$query->where('(c.published IN (0,1))');
		}

		$listOrder = $this->getState('list.ordering', 'jaar');
		$listDirn = $this->getState('list.direction', 'asc');
		if ($listOrder === 'plaats') {
			$listOrder = 's.naam';
		}
		$query->order($db->escape($listOrder) . ' ' . $db->escape($listDirn));

		return $query;
	}

}
