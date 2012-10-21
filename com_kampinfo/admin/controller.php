<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');


/**
 * General Controller of KampInfo component.
 */
class KampInfoController extends JControllerLegacy {

	/**
	 * @var		string	The default view.
	 * @since	1.6
	 */
	protected $default_view = 'hitprojects';
	
	function display($cachable = false, $urlparams = false) {

		require_once JPATH_COMPONENT.'/helpers/kampinfo.php';
		
		// Set the submenu
		KampInfoHelper :: addSubmenu(JRequest::getCmd('view', 'hitprojects'));

		$view	= JRequest::getCmd('view', 'hitprojects');
		$layout = JRequest::getCmd('layout', 'default');
		$id		= JRequest::getInt('id');

		// Check for edit form.
		if ($view == 'hitprojects' && $layout == 'edit' && !$this->checkEditId('com_kampinfo.edit.hitproject', $id)) {

			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_kampinfo&view=hitprojects', false));

			return false;
		}
		elseif ($view == 'hitsite' && $layout == 'edit' && !$this->checkEditId('com_kampinfo.edit.hitsite', $id)) {

			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_kampinfo&view=hitsites', false));

			return false;
		}
		elseif ($view == 'hitcamp' && $layout == 'edit' && !$this->checkEditId('com_kampinfo.edit.hitcamp', $id)) {

			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_kampinfo&view=hitcamps', false));

			return false;
		}

		parent::display();

		return $this;	}
}