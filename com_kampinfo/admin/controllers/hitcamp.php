<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
 
/**
 * HitCamp Controller
 */
class KampInfoControllerHitCamp extends JControllerForm {
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
		if( !empty( $id ) ){
			$user = JFactory::getUser();
			return $user->authorise("hitcamp.edit", "com_kampinfo.hitcamp." . $id );
		}
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
		$model = $this->getModel('HitCamp', 'KampInfoModel');
	
		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_kampinfo&view=hitcamps' . $this->getRedirectToListAppend(), false));
	
		return parent::batch($model);
	}
}