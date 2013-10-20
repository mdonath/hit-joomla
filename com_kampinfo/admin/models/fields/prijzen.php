<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper :: loadFieldClass('list');

class JFormFieldPrijzen extends JFormFieldList {

	protected $type = 'Prijzen';

	// getLabel() left out

	public function getOptions() {
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/../com_kampinfo/helpers/kampinfo.php';

		$options = KampInfoHelper :: getHitPrijzenOptions();
		
		// Merge any additional options in the XML definition.
		$options = array_merge(parent :: getOptions(), $options);
		
		return $options;
	}
}