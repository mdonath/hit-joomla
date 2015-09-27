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
				'shantiFormuliernummer',
				'naam',
				'gereserveerd',
				'aantalDeelnemers',
				'published'
			);
		}

		//call the parent constructor
		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null) {
		$app = JFactory::getApplication('administrator');
	
		// for filtering
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
	
		$jaar = $app->getUserStateFromRequest($this->context . '.filter.jaar', 'filter_jaar', '', 'string');
		if ($jaar == "") {
			$jaar = JComponentHelper::getParams('com_kampinfo')->get('huidigeActieveJaar');
			$app->setUserState($this->context.'.filter.jaar', $jaar);
		}
		$this->setState('filter.jaar', $jaar);
	
		$plaats = $app->getUserStateFromRequest($this->context . '.filter.plaats', 'filter_plaats', '', 'string');
		if ($plaats == '-1') {
			$plaats == "";
		}
		$this->setState('filter.plaats', $plaats);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '', 'string');
		$this->setState('filter.published', $published);

		parent::populateState('jaar', 'asc');
	}

	protected function getListQuery() {
		
		// Create a new query object.           
		$db = JFactory::getDBO();
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

	function endsWith($haystack, $needle) {
		return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
	}
	
}