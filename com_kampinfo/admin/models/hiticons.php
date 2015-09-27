<?php


// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 * HitIcons Model.
 */
class KampInfoModelHitIcons extends JModelList {

	public function __construct($config = array ()) {
		//check for the configuration array
		if (empty ($config['filter_fields'])) {
			//set the filter fields
			//this filter fields is associated with the grid.sort on the default.php view template
			$config['filter_fields'] = array (
				'id',
				'volgorde',
				'soort'
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
	
		$soort = $app->getUserStateFromRequest($this->context . '.filter.soort', 'filter_soort', '', 'string');
		$this->setState('filter.soort', $soort);
	
		// for sorting
		parent::populateState('volgorde', 'asc');
	}

	protected function getListQuery() {
		// Create a new query object.           
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('i.*');
		$query->from('#__kampinfo_hiticon i');

		$filterSearch = $this->getState('filter.search');
		if (!empty ($filterSearch)) {
			$term = $db->quote('%'.$db->escape($filterSearch).'%');
			$query->where('(i.bestandsnaam LIKE ' . $term . ') OR (i.tekst LIKE ' . $term . ')');
		}
		
		$filterSoort = $this->getState('filter.soort');
		if (!empty ($filterSoort) && $filterSoort != '-1') {
			$query->where('(i.soort = ' . $db->quote($db->escape($filterSoort)) . ')');
		}

		$listOrder = $this->getState('list.ordering', 'volgorde');
		$listDirn = $this->getState('list.direction', 'asc');
		$query->order($db->escape($listOrder) . ' ' . $db->escape($listDirn));

		return $query;
	}
}