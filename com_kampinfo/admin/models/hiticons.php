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
			//this filter fileds is associated with the grid.sort on the default.php view template
			$config['filter_fields'] = array (
				'id',
				'volgorde',
				'soort'
			);
		}

		//call the parent constructor
		parent :: __construct($config);
	}

	protected function getListQuery() {
		$listOrder = $this->state->get('list.ordering', 'volgorde');
		$listDirn = $this->state->get('list.direction', 'asc');
		$filterSearch = $this->getState('filter.search');
		$filterSoort = $this->getState('filter.soort');
		
		// Create a new query object.           
		$db = JFactory :: getDBO();
		$query = $db->getQuery(true);
		$query->select('i.*');
		$query->from('#__kampinfo_hiticon i');

		if (!empty ($filterSearch)) {
			$term = $db->quote('%'.$db->getEscaped($filterSearch).'%');
			$query->where('(i.bestandsnaam LIKE ' . $term . ') OR (i.tekst LIKE ' . $term . ')');
		}
		if (!empty ($filterSoort)) {
			$query->where('(i.soort = ' . $db->quote($db->getEscaped($filterSoort)) . ')');
		}

		$query->order($db->getEscaped($listOrder) . ' ' . $db->getEscaped($listDirn));

		return $query;
	}

	protected function populateState($ordering = null, $direction = null) {
		// for sorting
		parent :: populateState('volgorde', 'asc');

		// for filtering
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$state = $this->getUserStateFromRequest($this->context . '.filter.soort', 'filter_soort', '', 'string');
		$this->setState('filter.soort', $state);
	}
}