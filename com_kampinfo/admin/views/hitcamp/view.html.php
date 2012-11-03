<?php


// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import default KampInfo views
include_once dirname(__FILE__).'/../default/view.html.php';


/**
 * HitCamp View.
 */
class KampInfoViewHitCamp extends KampInfoViewItemDefault {

	function __construct($config = null) {
		$this->prefix = 'COM_KAMPINFO_HITCAMP';
		$this->entityName = 'hitcamp';
		parent :: __construct($config);
	}

}