<?php defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

require_once JPATH_COMPONENT_ADMINISTRATOR.'/../com_kampinfo/helpers/kampinfo.php';


class JFormFieldSite extends JFormFieldList {

	protected $type = 'Site';

	// getLabel() left out

	public function getOptions() {
		$filter = $this->form->getData()->get('filter');
		if ($filter != NULL && property_exists($filter, 'jaar') && isset($filter->jaar) && $filter->jaar != -1) {
			$options = KampInfoHelper::getHitSiteOptions($filter->jaar);
		} else {
			$options = KampInfoHelper::getHitSiteOptions();
		}
		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);
		
		return $options;
	}
}