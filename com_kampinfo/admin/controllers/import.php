<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');


class KampInfoControllerImport extends JControllerForm {

	public function importKampgegevens() {
		$model = $this->getModel('import');

		$model->importKampgegevens();

		$this->setRedirect(JRoute::_('index.php?option=com_kampinfo&view=import', false));
		return true;
	}

	public function importInschrijvingen() {
		$model = $this->getModel('import');
	
		$model->importInschrijvingen();
	
		$this->setRedirect(JRoute::_('index.php?option=com_kampinfo&view=import', false));
		return true;
	}

	public function importDeelnemers() {
		$model = $this->getModel('import');
	
		$model->importDeelnemergegevens();
	
		$this->setRedirect(JRoute::_('index.php?option=com_kampinfo&view=import', false));
		return true;
	}
	
}
