<?php defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 * HitSites Model.
 */
class KampInfoModelHitSites extends JModelList {

	public function __construct($config = array ()) {
		//check for the configuration array
		if (empty ($config['filter_fields'])) {
			//set the filter fields
			//this filter fileds is associated with the grid.sort on the default.php view template
			$config['filter_fields'] = array (
				'id',
				'jaar',
				'naam',
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
		$filterPublished = $this->getState('filter.published');

		// Create a new query object.           
		$db = JFactory :: getDBO();
		$query = $db->getQuery(true);
		$query->select('s.id,s.hitproject_id,s.naam,s.published,s.akkoordHitPlaats,s.hitCourantTekst,s.contactPersoonNaam,s.contactPersoonEmail,s.contactPersoonTelefoon');
		$query->from('#__kampinfo_hitsite s');

		$query->select('p.jaar as jaar');
		$query->join('LEFT', '#__kampinfo_hitproject AS p ON s.hitproject_id=p.id');

		if (!empty ($filterSearch)) {
			$query->where('(s.naam LIKE ' . $db->quote('%'.$db->getEscaped($filterSearch).'%') . ')');
		}
		if (!empty ($filterJaar) and ($filterJaar != "-1")) {
			$query->where('(p.id = ' . (int)($db->getEscaped($filterJaar)) . ')');
		}
		if (is_numeric($filterPublished)) {
			$query->where('(s.published = '. (int)($db->getEscaped($filterPublished)) .')');
		} elseif ($filterPublished === '') {
			$query->where('(s.published IN (0,1))');
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
		if ($state == "") {
			$params = &JComponentHelper::getParams('com_kampinfo');
			$state = $params->get('huidigeActieveJaar');
		}
		if ($state == '-1') {
			$state = "";
		}
		$this->setState('filter.jaar', $state);
		
		$state = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '', 'string');
		$this->setState('filter.published', $state);
	}
}