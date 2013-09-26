<?php


// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 * HitCamps Model.
 */
class KampInfoModelHitCamps extends JModelList {

	public function __construct($config = array ()) {
		//check for the configuration array
		if (empty ($config['filter_fields'])) {
			//set the filter fields
			//this filter fileds is associated with the grid.sort on the default.php view template
			$config['filter_fields'] = array (
				'id',
				'jaar',
				'plaats',
				'deelnemersnummer',
				'shantiFormuliernummer',
				'naam',
				'gereserveerd',
				'aantalDeelnemers',
				'published'
			);
		}

		//call the parent constructor
		parent :: __construct($config);
	}
	protected function getListQuery() {
		$listOrder = $this->state->get('list.ordering', 'jaar');
		$listDirn = $this->state->get('list.direction', 'asc');
		$filterSearch = $this->getState('filter.search');
		$filterJaar = $this->getState('filter.jaar');
		$filterPlaats = $this->getState('filter.plaats');
		$filterPublished = $this->getState('filter.published');
		
		// Create a new query object.           
		$db = JFactory :: getDBO();
		$query = $db->getQuery(true);
		$query->select('c.*');
		$query->from('#__kampinfo_hitcamp c');

		$query->select('s.naam as plaats, s.id as hitsite_id');
		$query->join('LEFT', '#__kampinfo_hitsite AS s ON c.hitsite=s.code');

		$query->select('p.jaar as jaar, p.id as hitproject_id');
		$query->join('LEFT', '#__kampinfo_hitproject AS p ON s.jaar=p.jaar');

		if (!empty ($filterSearch)) {
			$query->where('(c.naam LIKE ' . $db->quote('%'.$db->getEscaped($filterSearch).'%') . ')');
		}
		if (!empty ($filterJaar)) {
			$query->where('(p.jaar = ' . (int)($db->getEscaped($filterJaar)) . ')');
		}
		if (!empty ($filterPlaats)) {
			$query->where('(s.code = ' . $db->quote($db->getEscaped($filterPlaats)) . ')');
		}
		if (is_numeric($filterPublished)) {
			$query->where('(c.published = '. (int)($db->getEscaped($filterPublished)) .')');
		} elseif ($filterPublished === '') {
			$query->where('(c.published IN (0,1))');
		}
		
		if ($listOrder === 'plaats') {
			$listOrder = 's.naam';
		}
		$query->order($db->getEscaped($listOrder) . ' ' . $db->getEscaped($listDirn));

		return $query;
	}

	protected function populateState($ordering = null, $direction = null) {
		// for sorting
		parent :: populateState('jaar', 'asc');

		// for filtering
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$state = $this->getUserStateFromRequest($this->context . '.filter.jaar', 'filter_jaar', '', 'string');
		$this->setState('filter.jaar', $state);
		$state = $this->getUserStateFromRequest($this->context . '.filter.plaats', 'filter_plaats', '', 'string');
		$this->setState('filter.plaats', $state);
		$state = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '', 'string');
		$this->setState('filter.published', $state);
	}
}