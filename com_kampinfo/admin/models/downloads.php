<?php defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 * Downloads Model.
 */
class KampInfoModelDownloads extends JModelList {

	public function __construct($config = array ()) {
		//check for the configuration array
		if (empty ($config['filter_fields'])) {
			//set the filter fields
			//this filter fileds is associated with the grid.sort on the default.php view template
			$config['filter_fields'] = array (
				'id',
				'jaar',
				'soort',
				'bijgewerktOp'
			);
		}

		//call the parent constructor
		parent::__construct($config);
	}
	protected function getListQuery() {
		$listOrder = $this->getState('list.ordering', 'bijgewerktOp');
		$listDirn = $this->getState('list.direction', 'desc');
		$filterSearch = $this->getState('filter.search');
		$filterJaar = $this->getState('filter.jaar');

		// Create a new query object.           
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('d.*');
		$query->from('#__kampinfo_downloads d, #__kampinfo_hitproject p');
		$query->where('d.jaar = p.jaar');
		if (!empty ($filterJaar)) {
			$query->where('(p.id= ' . (int)($db->escape($filterJaar)) . ')');
		}

		$query->order($db->escape($listOrder) . ' ' . $db->escape($listDirn));

		return $query;
	}

	protected function populateState($ordering = null, $direction = null) {
		// for sorting
		parent::populateState('bijgewerktOp', 'desc');

		// for filtering
		$state = $this->getUserStateFromRequest($this->context . '.filter.jaar', 'filter_jaar', '', 'string');
		$this->setState('filter.jaar', $state);
		$state = $this->getUserStateFromRequest($this->context . '.filter.soort', 'filter_soort', '', 'string');
		$this->setState('filter.soort', $state);
	}
}