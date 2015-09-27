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
				'jaar',
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
		
		$state = $app->getUserStateFromRequest($this->context . '.filter.jaar', 'filter_jaar', '');
		if ($state === '') {
			// gebruik huidige actieve jaar
			$state = JComponentHelper::getParams('com_kampinfo')->get('huidigeActieveJaar');
			// update filter op het scherm
			$app->setUserState($this->context . '.filter.jaar', $state);
		}
		$this->setState('filter.jaar', $state);
	
		$state = $app->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '', 'string');
		$this->setState('filter.published', $state);

		// for sorting
		parent::populateState('jaar', 'asc');
	}

	protected function getListQuery() {
		$app = JFactory::getApplication('administrator');
		$db = JFactory::getDBO();
		$query = parent::getListQuery();
		$query->select('s.id,s.hitproject_id,s.naam,s.published,s.akkoordHitPlaats,s.hitCourantTekst,s.contactPersoonNaam,s.contactPersoonEmail,s.contactPersoonTelefoon,s.projectcode');
		$query->from('#__kampinfo_hitsite s');

		$query->select('p.jaar as jaar');
		$query->join('LEFT', '#__kampinfo_hitproject AS p ON s.hitproject_id=p.id');

		$filterSearch = $this->getState('filter.search');
		if (!empty ($filterSearch)) {
			$query->where('(s.naam LIKE ' . $db->quote('%'.$db->escape($filterSearch).'%') . ')');
		}

		$filterJaar = $this->getState('filter.jaar');
		if (!empty ($filterJaar) and ($filterJaar != "-1")) {
			$query->where('(p.id = ' . (int)($db->escape($filterJaar)) . ')');
		}

		$filterPublished = $this->getState('filter.published');
		if (is_numeric($filterPublished)) {
			$query->where('(s.published = '. (int)($db->escape($filterPublished)) .')');
		} elseif ($filterPublished === '') {
			$query->where('(s.published IN (0,1))');
		}
		
		$listOrder = $this->getState('list.ordering', 'jaar');
		$listDirn = $this->getState('list.direction', 'asc');
		$query->order($db->escape($listOrder) . ' ' . $db->escape($listDirn));

		return $query;
	}

}