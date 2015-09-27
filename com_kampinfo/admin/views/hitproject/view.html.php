<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import default KampInfo views
include_once dirname(__FILE__).'/../default/view.html.php';


/**
 * HitProject View.
 */
class KampInfoViewHitProject extends KampInfoViewItemDefault {

	function __construct($config = null) {
		$this->prefix = 'COM_KAMPINFO_HITPROJECT';
		$this->entityName = 'hitproject';
		parent::__construct($config);
	}

}