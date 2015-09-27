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
		parent::__construct($config);
	}

	protected function authoriseItems($items) {
		$ids = array();
		$hitsite_ids = array();
		if ($items) {
			foreach ($items as $row) {
				$ids[] = $row->id;
				$hitsite_ids[] = $row->hitsite_id;
			}
		}
		// What Access Permissions does this user have? What can (s)he do?
		$this->canDo = KampInfoHelper::getActions($this->entityName, $ids);
		
		// Verzamel ook de permissies op hitplaats niveau, nodig voor accorderen van kampgegevens door plaats
		$hitsite_ids = array_unique($hitsite_ids);
		$hitsiteCando =  KampInfoHelper::getActions('hitsite', $hitsite_ids);
		foreach ($hitsiteCando as $k=>$v) {
			$this->canDo->set($k, $v);
		}
	}
}