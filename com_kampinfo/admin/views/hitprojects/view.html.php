<?php defined('_JEXEC') or die('Restricted access');

// import default KampInfo view
include_once dirname(__FILE__).'/../default/view.html.php';

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HitProjects View.
 */
class KampInfoViewHitProjects extends KampInfoViewListDefault {

	function __construct($config = null) {
		$this->toolbarTitle = 'COM_KAMPINFO_HITPROJECTS_MANAGER';
		$this->entityName = 'hitproject';
		parent::__construct($config);
	}

}