<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper :: loadFieldClass('list');

require_once JPATH_COMPONENT_ADMINISTRATOR.'/../com_kampinfo/helpers/kampinfo.php';


class JFormFieldSite extends JFormFieldList {

	protected $type = 'Site';

	// getLabel() left out

	public function getOptions() {

		$options = KampInfoHelper :: getHitSiteOptions();
		
		// Merge any additional options in the XML definition.
		$options = array_merge(parent :: getOptions(), $options);
		
		return $options;
	}
}