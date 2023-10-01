<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Field;

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;

class ProjectField extends ListField {

	protected $type = 'Project';

	// getLabel() left out

	public function getOptions() {
		return array_merge(
			parent::getOptions(),
			KampInfoHelper::getHitProjectOptions()
		);
	}
}
