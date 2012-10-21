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
				'jaar'
			);
		}

		//call the parent constructor
		parent :: __construct($config);
	}

	protected function getListQuery() {
		$listOrder = $this->state->get('list.ordering', 'jaar');
		$listDirn = $this->state->get('list.direction', 'asc');

		// Create a new query object.           
		$db = JFactory :: getDBO();
		$query = $db->getQuery(true);
		$query->select('id,jaar,inschrijvingStartdatum,inschrijvingEinddatum,inschrijvingWijzigenTotDatum,inschrijvingKosteloosAnnulerenDatum,inschrijvingGeenRestitutieDatum,inningsdatum');
		$query->from('#__kampinfo_hitproject');

		$query->order($db->getEscaped($listOrder) . ' ' . $db->getEscaped($listDirn));
		return $query;
	}

	protected function populateState($ordering = null, $direction = null) {
		parent :: populateState('jaar', 'asc');
	}
}