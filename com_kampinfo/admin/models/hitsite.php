<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * HitSite Model.
*/
class KampInfoModelHitSite extends JModelAdmin {
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param       type    The table type to instantiate
	 * @param       string  A prefix for the table class name. Optional.
	 * @param       array   Configuration array for model. Optional.
	 * @return      JTable  A database object
	 * @since       2.5
	 */
	public function getTable($type = 'HitSite', $prefix = 'KampInfoTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Method to get the record form.
	 *
	 * @param       array   $data           Data for the form.
	 * @param       boolean $loadData       True if the form is to load its own data (default case), false if not.
	 * @return      mixed   A JForm object on success, false on failure
	 * @since       2.5
	 */
	public function getForm($data = array(), $loadData = true) {
		// Get the form: hitsite.xml
		$form = $this->loadForm('com_kampinfo.hitsite', 'hitsite',
				array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
	public function getKampen() {
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$item = $this->getItem();
		$siteId = $item->id;
		return $this->getVolledigeKampenVanPlaats($db, $siteId);
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return      mixed   The data for the form.
	 * @since       2.5
	 */
	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_kampinfo.edit.hitsite.data', array());
		if (empty($data)) {
			$data = $this->getItem();
		}
		return $data;
	}

	/**
	 * Method to check if it's OK to delete a hitcamp. Overwrites JModelAdmin::canDelete
	 */
	protected function canDelete($record) {
		if (!empty($record->id)) {
			$user = JFactory::getUser();
			return $user->authorise("hitsite.delete", "com_kampinfo.hitsite." . $record->id);
		}
	}

	public function	batch($commands, $pks, $contexts) {
		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);
		
		// Remove any values of zero.
		if (array_search(0, $pks, true)) {
			unset($pks[array_search(0, $pks, true)]);
		}
		
		if (empty($pks)) {
			$this->setError(JText::_('Niets geselecteerd!'));
			return false;
		}
		
		$what = $commands['group_action'];
		if ($what == 'akkoordPlaats') {
			$this->akkoordPlaats($pks, 1);
		} else if ($what == 'nietAkkoordPlaats') {
			$this->akkoordPlaats($pks, 0);
		} else if ($what == 'copyKampen') {
			$this->batchCopyKampen($pks);
		} else {
			$this->setError(JText::_('Ik weet niet wat ik moet doen!'));
			return false;
		}
		
		
		return true;
	} 
	
	public function akkoordPlaats($ids, $value) {
		$cids = implode(',', $ids);
	
		$db = JFactory::getDBO();
		$query = 'UPDATE #__kampinfo_hitsite SET akkoordHitPlaats = '.(int) $value . ' WHERE id IN ( '.$cids.' )';
		$db->setQuery($query);
		$result = $db->query();
	}
	
	public function batchCopyKampen($ids) {
		foreach ($ids as $id) {
			$this->copyKampen($id);
		}	
	}

	public function copyKampen($siteId) {
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$t = $this->getTable();
		$t->load($siteId);
		$plaatsNaam = $t->naam;
		
		// Heeft de plaats voor dit jaar al kamponderdelen -> stop
		if ($this->getKampenVanPlaats($db, $siteId)) {
			$app->enqueueMessage('HIT '.$plaatsNaam . ' heeft voor dit jaar al kamponderdelen in KampInfo! Er is niets gecopieerd.', 'error');
			return;
		}
		
		// Als de plaats er vorig jaar nog niet was -> stop
		$plaatsIdVorigJaar = $this->getHitPlaatsVanVorigJaar($db, $siteId);
		if (empty($plaatsIdVorigJaar)) {
			$app->enqueueMessage('HIT '.$plaatsNaam . ' bestond vorig jaar nog niet!', 'error');
			return;
		}
		
		// Als er vorig jaar geen kamponderdelen waren (maar wel een plaats?) -> stop
		$teCopierenKampen = $this->getKampenVanPlaats($db, $plaatsIdVorigJaar);
		if (empty($teCopierenKampen)) {
			$app->enqueueMessage('HIT '.$plaatsNaam . ' had vorig jaar geen kamponderdelen?! Niets gecopieerd!', 'error');
			return;
		}
		
		$namen = array();
		foreach ($teCopierenKampen as $row) {
			$kamp = $this->getTable('HitCamp');
			if ($kamp->load($row->id)) {
				$namen[] = $kamp->naam;
				
				$kamp->hitsite_id = $siteId;
				$kamp->id = null;			
				$kamp->shantiFormuliernummer = null;
				$kamp->aantalSubgroepen = 0;
				$kamp->aantalDeelnemers = 0;
				$kamp->gereserveerd = 0;
				$kamp->akkoordHitKamp = 0;
				$kamp->akkoordHitPlaats = 0;
				$kamp->published = 0;
				// Zet startdag over naar dit jaar
				$kamp->startDatumTijd = $this->herberekenDatum($kamp->startDatumTijd);
				$kamp->eindDatumTijd = $this->herberekenDatum($kamp->eindDatumTijd);
				
				$kamp->store();
			}
		}
		
		$app->enqueueMessage('Bij HIT '.$plaatsNaam . ' zijn '. count($namen) .' kampen overgezet!');
		$app->enqueueMessage("'" . implode("', '", $namen) . "'");
	}
	
 	private function getKampenVanPlaats($db, $siteId) {
		$query = $db->getQuery(true)
			-> select('c.id')
			-> from($db->quoteName('#__kampinfo_hitcamp', 'c'))
			-> where($db->quoteName('c.hitsite_id') .'='. (int) $db->escape($siteId));
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	private function getVolledigeKampenVanPlaats($db, $siteId) {
		$query = $db->getQuery(true)
		-> select('c.*, p.jaar')
		-> from($db->quoteName('#__kampinfo_hitcamp', 'c'))
		-> join('LEFT', '#__kampinfo_hitsite AS s ON (c.hitsite_id = s.id)')
		-> join('LEFT', '#__kampinfo_hitproject AS p ON (s.hitproject_id = p.id)')
		-> where($db->quoteName('c.hitsite_id') .'='. (int) $db->escape($siteId))
		-> order('c.naam');
		$db->setQuery($query);
		
		$result = $db->loadObjectList();
		
		// expand icons
		foreach ($result as $kamp) {
			$query = $db->getQuery(true);
			$query->select('i.bestandsnaam as naam, i.tekst, i.volgorde');
			$query->from('#__kampinfo_hiticon i');
			
			$values=array();
			foreach(explode(',', $kamp->icoontjes) as $naam) {
				$values[] = $db->quote($db->escape($naam));
			}
			$query->where('i.bestandsnaam in (' . implode(',', $values) . ')');
			$query->order('i.volgorde');
			
			$db->setQuery($query);
			$icons = $db->loadObjectList();
			$kamp->icoontjes = $icons;
		}		
		
		return $result;
	}
	

	private function getHitPlaatsVanVorigJaar($db, $siteId) {
		$query = $db->getQuery(true)
			-> select('s_vorig.id')
			-> from($db->quoteName('#__kampinfo_hitsite', 's_now'))
			-> join('LEFT', '#__kampinfo_hitproject AS p_now ON (s_now.hitproject_id = p_now.id)')
			-> join('LEFT', '#__kampinfo_hitproject AS p_vorig ON (p_now.jaar - 1 = p_vorig.jaar)')
			-> join('LEFT', '#__kampinfo_hitsite AS s_vorig ON (p_vorig.id = s_vorig.hitproject_id and s_now.naam = s_vorig.naam)')
			-> where($db->quoteName('s_now.id') .'='. (int) $db->escape($siteId));
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	private function herberekenDatum($datum) {
		$DATABASE_DATETIMEFORMAT = 'Y-m-d G:i:s';
		$origineel = DateTime::createFromFormat($DATABASE_DATETIMEFORMAT, $datum);
		$vorigJaar = (int) $origineel->format('Y');
		$diffStart = KampInfoHelper::eersteHitDag($vorigJaar)->diff($origineel);
		$nieuweStart = KampInfoHelper::eersteHitDag($vorigJaar + 1)->add($diffStart);
		return $nieuweStart->format($DATABASE_DATETIMEFORMAT);
	}
	
	
}