<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Service\HTML;

use Joomla\CMS\HTML\HTMLHelper;


defined('_JEXEC') or die;

class Akkoord {
   
	public function akkoordplaats($value, $i, $prefix = '', $enabled = true, $translate = true, $checkbox = 'cb') {
		$states = array(
				// (      'task',       'text',  	    'active_title'  			'inactive_title','tip','active_class', 'inactive_class')
				0 => array('akkoordPlaats',     'Niet akkoord Plaats', 'Maak Akkoord Plaats',      'Niet Akkoord Plaats', false, 'unpublish', 'unpublish'),
				1 => array('nietAkkoordPlaats', 'Akkoord Plaats',      'Maak Niet Akkoord Plaats', 'Akoord Plaats',       false, 'publish',   'publish'),
		);
		return $this->akkoord($states, $value, $i, $prefix, $enabled, $translate, $checkbox);
	}

	public function akkoordkamp($value, $i, $prefix = '', $enabled = true, $translate = true, $checkbox = 'cb') {
		$states = array(
				// (      'task',       'text',  	    'active_title'  			'inactive_title','tip','active_class', 'inactive_class')
				0 => array('akkoordKamp',     'Niet akkoord Kamp', 'Maak Akkoord Kamp',      'Niet Akkoord Kamp', false, 'unpublish', 'unpublish'),
				1 => array('nietAkkoordKamp', 'Akkoord Kamp',      'Maak Niet Akkoord Kamp', 'Akoord Kamp',       false, 'publish',   'publish'),
		);
		return $this->akkoord($states, $value, $i, $prefix, $enabled, $translate, $checkbox);
	}

	public function akkoord($states, $value, $i, $prefix, $enabled, $translate, $checkbox) {
		return HTMLHelper::_('jgrid.state', $states, $value, $i, $prefix, $enabled, $translate, $checkbox);
	} 
}