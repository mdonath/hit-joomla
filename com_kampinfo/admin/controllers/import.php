<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');


class KampInfoControllerImport extends JControllerForm {

	public function import() {
		$model = $this->getModel('import');

		$model->import();

		$this->setRedirect(JRoute :: _('index.php?option=com_kampinfo&view=import', false));
		return true;
	}
}