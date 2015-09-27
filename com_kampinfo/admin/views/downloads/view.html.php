<?php defined('_JEXEC') or die('Restricted access');

// import default KampInfo views
include_once dirname(__FILE__).'/../default/view.html.php';

/**
 * Downloads View.
 */
class KampInfoViewDownloads extends KampInfoViewListDefault {

	function __construct($config = null) {
		$this->toolbarTitle = 'COM_KAMPINFO_DOWNLOADS_MANAGER';
		$this->entityName = 'download';
		parent::__construct($config);
	}
	
	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::_($this->toolbarTitle), 'kampinfo');
	
		// JToolBarHelper::deleteListX('', $this->entityName . 's.delete');
		if (JFactory::getUser()->authorise('core.admin', 'com_kampinfo'))     {
			JToolBarHelper::preferences('com_kampinfo');
		}
	}

}