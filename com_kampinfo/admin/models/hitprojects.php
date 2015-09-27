<?php


// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 * HitProjects Model.
 */
class KampInfoModelHitProjects extends JModelList {

	public function __construct($config = array ()) {
		//check for the configuration array
		if (empty ($config['filter_fields'])) {
			//set the filter fields
			//this filter fields is associated with the grid.sort on the default.php view template
			$config['filter_fields'] = array (
				'jaar', 'p.jaar'
			);
		}

		//call the parent constructor
		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null) {
		// Initialise variables.
		$app = JFactory::getApplication('administrator');
		
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$jaar = $app->getUserStateFromRequest($this->context . '.filter.jaar', 'filter_jaar', '', 'string');
		$this->setState('filter.jaar', $jaar);
		
		parent::populateState('jaar', 'desc');
	}

	protected function getListQuery() {
		// Create a new query object.           
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('p.*');
		$query->from('#__kampinfo_hitproject p');

		$jaar = $this->getState('filter.search');
		if (is_numeric($jaar)) {
			$query->where('p.jaar = '. (int) $jaar);
		}
		
		// ordering clause
		$listOrder = $this->getState('list.ordering', 'jaar');
		$listDirn = $this->getState('list.direction', 'desc');
		$query->order($db->escape($listOrder) . ' ' . $db->escape($listDirn));

		return $query;
	}
}