<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * HitSite Controller
 */
class KampInfoControllerHitSite extends JControllerForm {
	/**
	 * Implement to allowAdd or not
	 *
	 * Not used at this time (but you can look at how other components use it....)
	 * Overwrites: JControllerForm::allowAdd
	 *
	 * @param array $data
	 * @return bool
	 */
	protected function allowAdd($data = array()) {
		return parent::allowAdd($data);
	}

	/**
	 * Implement to allow edit or not
	 * Overwrites: JControllerForm::allowEdit
	 *
	 * @param array $data
	 * @param string $key
	 * @return bool
	 */
	protected function allowEdit($data = array(), $key = 'id') {
		$id = isset( $data[ $key ] ) ? $data[ $key ] : 0;
		if( !empty( $id ) ) {
			$user = JFactory::getUser();
			return $user->authorise("hitsite.edit", "com_kampinfo.hitsite." . $id );
		}
	}
	
	/**
	 * Copieert alle kampen van vorig jaar.
	 * @param string $model
	 */
	public function copyCamps($model = null) {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$model = $this->getModel('HitSite', 'KampInfoModel');
		$recordId = JRequest::getInt('id');
		$this->setRedirect(JRoute::_('index.php?option=com_kampinfo&view=hitsite' . $this->getRedirectToItemAppend($recordId), false));
		$model->copyKampen($recordId);
		
		return null;
	}

	/**
	 * Method to run batch operations.
	 *
	 * @param   object  $model  The model.
	 *
	 * @return  boolean  True on success, false on failure
	 *
	 * @since   2.5
	 */
	public function batch($model = null) {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Set the model
		$model = $this->getModel('HitSite', 'KampInfoModel');
	
		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_kampinfo&view=hitsites' . $this->getRedirectToListAppend(), false));
	
		return parent::batch($model);
	}
	
}