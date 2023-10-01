<?php

use Joomla\CMS\Factory;

defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
include_once dirname(__FILE__) . '/kampinfomodelparent.php';

/**
 * KampInfo Overzichtplaats Model
 */
class KampInfoModelOverzichtplaats extends KampInfoModelParent {

	public function getPlaats() {
		$input = Factory::getApplication()->input;
		$hitsiteId = $input->getString('hitsite_id', '');

		$plaats = $this->getHitPlaats($hitsiteId);

		$iconenLijst = $this->getIconenLijst();
		$plaats->kampen = $this->getHitKampen($plaats->id, $iconenLijst);

		return $plaats;
	}

}