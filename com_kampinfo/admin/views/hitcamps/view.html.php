<?php


// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import default KampInfo views
include_once dirname(__FILE__).'/../default/view.html.php';

/**
 * HitCamps View.
 */
class KampInfoViewHitCamps extends KampInfoViewListDefault {

	function __construct($config = null) {
		$this->toolbarTitle = 'COM_KAMPINFO_HITCAMPS_MANAGER';
		$this->entityName = 'hitcamp';
		parent :: __construct($config);
	}

}