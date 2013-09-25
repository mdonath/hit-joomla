<?php


// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import default KampInfo view
include_once dirname(__FILE__).'/../default/view.html.php';

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Info View.
 */
class KampInfoViewInfo extends KampInfoViewListDefault {

	function __construct($config = null) {
		$this->toolbarTitle = 'COM_KAMPINFO_INFO_MANAGER';
		parent :: __construct($config);
	}

}