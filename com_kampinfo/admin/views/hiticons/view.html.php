<?php defined('_JEXEC') or die('Restricted access');

// import default KampInfo view
include_once dirname(__FILE__).'/../default/view.html.php';

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HitIcons View.
*/
class KampInfoViewHitIcons extends KampInfoViewListDefault {

	function __construct($config = null) {
		$this->toolbarTitle = 'COM_KAMPINFO_HITICONS_MANAGER';
		$this->entityName = 'hiticon';
		parent::__construct($config);
	}

}