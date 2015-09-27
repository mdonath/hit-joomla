<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import default KampInfo views
include_once dirname(__FILE__).'/../default/view.html.php';


/**
 * HitSite View.
 */
class KampInfoViewHitSite extends KampInfoViewItemDefault {

	function __construct($config = null) {
		$this->prefix = 'COM_KAMPINFO_HITSITE';
		$this->entityName = 'hitsite';
		parent::__construct($config);
	}
}