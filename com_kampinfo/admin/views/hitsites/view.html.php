<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import default KampInfo view
include_once dirname(__FILE__).'/../default/view.html.php';

// import Joomla view library
jimport('joomla.application.component.view');


/**
 * HitSites View.
 */
class KampInfoViewHitSites extends KampInfoViewListDefault {

	function __construct($config = null) {
		$this->toolbarTitle = 'COM_KAMPINFO_HITSITES_MANAGER';
		$this->entityName = 'hitsite';
		parent :: __construct($config);
	}

}