<?php

/**
 * KampInfo component helper.
 */
abstract class KampInfoHelper {

	/**         
	 * Get the actions         
	 */
	public static function getActions($entity = 'component', $entityIds = null) {
		jimport('joomla.access.access');
		$result = new JObject;

		$user = JFactory::getUser();
		$actions = JAccess::getActions('com_kampinfo', $entity);
		
		if (empty($entityIds)) {
			foreach ($actions as $action) {
				$result->set($action->name, $user->authorise($action->name, 'com_kampinfo'));
			}
		} else {
			foreach ($actions as $action) {
				$result->set($action->name, $user->authorise($action->name, 'com_kampinfo'));
				if (self::startsWith($action->name, $entity) && !self::endsWith($action->name, 'create')) {
					foreach ($entityIds as $entityId) {
						$assetName = 'com_kampinfo.'.$entity.'.'. $entityId;
						$isAuth = $user->authorise($action->name, $assetName);
// 						echo ("assetname $assetName isAuth: $isAuth<br>");
						$result->set($action->name.'.'.$entityId, $isAuth);
						if (!empty($isAuth)) {
							$result->set($action->name, $isAuth);
						}
					}
				}
			}
		}

		/*
		echo '<h1>Entity: '.$entity.'</h1><ul>';
		foreach ($result as $k=>$v) {
			echo '<li>Action: '.$k.': "'. $v .'"</li>';
		}
		echo '</ul>';
		*/
		return $result;
	}

	/**
	 * 
	 * @param unknown $jaar
	 * @return De dat
	 */
	public static function eersteHitDag($jaar) { // VRIJDAG DUS
		$paasKalender = array(
				2004 => DateTime::createFromFormat('d-m-Y', '09-04-2004'),
				2005 => DateTime::createFromFormat('d-m-Y', '25-03-2005'),
				2006 => DateTime::createFromFormat('d-m-Y', '14-04-2006'),
				2007 => DateTime::createFromFormat('d-m-Y', '06-04-2007'),
				2008 => DateTime::createFromFormat('d-m-Y', '21-03-2008'),
				2009 => DateTime::createFromFormat('d-m-Y', '10-04-2009'),
				2010 => DateTime::createFromFormat('d-m-Y', '02-04-2010'),
				2011 => DateTime::createFromFormat('d-m-Y', '22-04-2011'),
				2012 => DateTime::createFromFormat('d-m-Y', '06-04-2012'),
				2013 => DateTime::createFromFormat('d-m-Y', '29-03-2013'),
				2014 => DateTime::createFromFormat('d-m-Y', '18-04-2014'),
				2015 => DateTime::createFromFormat('d-m-Y', '03-04-2015'),
				2016 => DateTime::createFromFormat('d-m-Y', '25-03-2016'),
				2017 => DateTime::createFromFormat('d-m-Y', '14-04-2017'),
				2018 => DateTime::createFromFormat('d-m-Y', '30-03-2018'),
				2019 => DateTime::createFromFormat('d-m-Y', '19-04-2019'),
				2020 => DateTime::createFromFormat('d-m-Y', '10-04-2020'),
				2021 => DateTime::createFromFormat('d-m-Y', '02-04-2021'),
		);
		return $paasKalender[$jaar];
	}

	public static function addSubmenu($submenu) {
		// set some global property
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-kampinfo ' . '{background-image: url(../media/com_kampinfo/images/kampinfo-48x48.png);}');

		// Retrieve authorisation
		$canDo = KampInfoHelper::getActions();
		
		// Show submenu items
		JHtmlSidebar::addEntry(JText::_('COM_KAMPINFO_SUBMENU_INFO'), 'index.php?option=com_kampinfo&view=info', $submenu == 'info');
		
		if ($canDo->get('hitproject.menu')) {
			JHtmlSidebar::addEntry(JText::_('COM_KAMPINFO_SUBMENU_HITPROJECTS'), 'index.php?option=com_kampinfo&view=hitprojects', $submenu == 'hitprojects');
		}
		if ($canDo->get('hitsite.menu')) {
			JHtmlSidebar::addEntry(JText::_('COM_KAMPINFO_SUBMENU_HITSITES'), 'index.php?option=com_kampinfo&view=hitsites', $submenu == 'hitsites');
		}
		if ($canDo->get('hitcamp.menu')) {
				JHtmlSidebar::addEntry(JText::_('COM_KAMPINFO_SUBMENU_HITCAMPS'), 'index.php?option=com_kampinfo&view=hitcamps', $submenu == 'hitcamps');
		}
		if ($canDo->get('core.admin')) {
			JHtmlSidebar::addEntry(JText::_('COM_KAMPINFO_SUBMENU_HITICONS'), 'index.php?option=com_kampinfo&view=hiticons', $submenu == 'hiticons');
			JHtmlSidebar::addEntry(JText::_('COM_KAMPINFO_SUBMENU_IMPORT'), 'index.php?option=com_kampinfo&view=import', $submenu == 'import');
			JHtmlSidebar::addEntry(JText::_('COM_KAMPINFO_SUBMENU_DOWNLOADS'), 'index.php?option=com_kampinfo&view=downloads', $submenu == 'downloads');
			JHtmlSidebar::addEntry('Overzichten', 'index.php?option=com_kampinfo&view=reports', $submenu == 'reports');
		}
				
		// Set the title
		if ($submenu == 'hitprojects') {
			$document->setTitle(JText::_('COM_KAMPINFO_HITPROJECTS_DOCTITLE'));
		}
		elseif ($submenu == 'hitsites') {
			$document->setTitle(JText::_('COM_KAMPINFO_HITSITES_DOCTITLE'));
		}
		elseif ($submenu == 'hitcamps') {
			$document->setTitle(JText::_('COM_KAMPINFO_HITCAMPS_DOCTITLE'));
		}
		elseif ($submenu == 'hiticons') {
			$document->setTitle(JText::_('COM_KAMPINFO_HITICONS_DOCTITLE'));
		}
		elseif ($submenu == 'import') {
			$document->setTitle(JText::_('COM_KAMPINFO_IMPORT_DOCTITLE'));
		}
		elseif ($submenu == 'downloads') {
			$document->setTitle(JText::_('COM_KAMPINFO_DOWNLOADS_DOCTITLE'));
		}
		elseif ($submenu == 'reports') {
			$document->setTitle("Overzichten");
		}
		elseif ($submenu == 'info') {
			$document->setTitle(JText::_('COM_KAMPINFO_INFO_DOCTITLE'));
		}
	}

	public static function getHitActiviteitOptions() {
		$options = array ();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('c.id As value, concat(c.naam, " (", s.naam, " - ", p.jaar, ")") As text');
		$query->from('#__kampinfo_hitcamp c');

		$query->select('s.naam as plaats, s.id as hitsite_id');
		$query->join('LEFT', '#__kampinfo_hitsite AS s ON c.hitsite_id=s.id');
		$query->join('LEFT', '#__kampinfo_hitproject AS p ON s.hitproject_id=p.id');
		
		$query->order('p.jaar, s.naam, c.naam');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}


	public static function getHitProjectOptions() {
		$options = array ();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('id As value, jaar As text');
		$query->from('#__kampinfo_hitproject');
		$query->order('jaar desc');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}
	
	public static function getHitJaarOptions() {
		$options = array ();
	
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
	
		$query->select('jaar As value, jaar As text');
		$query->from('#__kampinfo_hitproject');
		$query->order('jaar desc');
	
		// Get the options.
		$db->setQuery($query);
	
		$options = $db->loadObjectList();
	
		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
	
		return $options;
	}
	
	public static function getHitSiteOptions($hitproject_id = null) {
		$options = array ();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('s.id As value, concat(s.naam, " (", p.jaar,")") As text');
		$query->from('#__kampinfo_hitsite s');
		$query->join('LEFT', '#__kampinfo_hitproject AS p ON p.id=s.hitproject_id');
		if ($hitproject_id != null) {
			$query->where('s.hitproject_id = ' . (int)($db->escape($hitproject_id)));
		}
		$query->order('p.jaar desc, s.naam');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}

	public static function getHitIconOptions() {
		$options = array ();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('bestandsnaam As value, tekst As text');
		$query->from('#__kampinfo_hiticon');
		$query->order('volgorde');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}

	public static function getActivityAreaOptions() {
		return array (
			(object) array (
				"value" => "buitenleven",
				"text" => "Buitenleven"
			),
			(object) array (
				"value" => "expressie",
				"text" => "Expressie"
			),
			(object) array (
				"value" => "identiteit",
				"text" => "Identiteit"
			),
			(object) array (
				"value" => "internationaal",
				"text" => "Internationaal"
			),
			(object) array (
				"value" => "samenleving",
				"text" => "Samenleving"
			),
			(object) array (
				"value" => "sportenspel",
				"text" => "Sport en Spel"
			),
			(object) array (
				"value" => "uitdagend",
				"text" => "Uitdagende Scoutingtechnieken"
			),
			(object) array (
				"value" => "veiligengezond",
				"text" => "Veilig en Gezond"
			)
		);
	}

	public static function getTargetgroupOptions() {
		return array (
			(object) array (
				"value" => "bevers",
				"text" => "Bevers (5-7 jaar)"
			),
			(object) array (
				"value" => "welpen",
				"text" => "Welpen (7-11 jaar)"
			),
			(object) array (
				"value" => "scouts",
				"text" => "Scouts (11-15 jaar)"
			),
			(object) array (
				"value" => "explorers",
				"text" => "Explorers (15-18 jaar)"
			),
			(object) array (
				"value" => "roverscouts",
				"text" => "Roverscouts (18 t/m 21 jaar)"
			),
			(object) array (
				"value" => "plusscouts",
				"text" => "Plusscouts (21+)"
			),
			(object) array (
				"value" => "ndlg",
				"text" => "Volwassenen (ndlg)"
			)
		);
	}

	public static function getHitIconSoortOptions() {
		return array (
				"?" => "Gewoon",
				"B" => "Beweging",
				"I" => "Inschrijven",
				"O" => "Overnachten",
				"A" => "Afstand",
				"K" => "Koken"
		);
	}

	public static function getHitPrijzenOptions() {
		$params =JComponentHelper::getParams('com_kampinfo');
		$prijzenConfig = $params->get('mogelijkeDeelnamekosten');
		if (empty($prijzenConfig)) {
			$prijzenConfig = '35,40,45,50,55,60,65,70';
		}
		
		$prijzen = explode(',', $prijzenConfig);
		$result = array();
		foreach ($prijzen as $prijs) {
			$result[] = (object) array (
				"value" => $prijs,
				"text" => '€ '. $prijs
			);
		}
		return $result;
	}
	
	
	public static function reverse($date) {
		if ($date != '0000-00-00') {
			$date = new JDate($date);
			$date->setTimezone(self::getTimeZone());
			return $date->format('d-m-Y', true);
		}
		return $date;
	}
	
	/**
	 * Returns the userTime zone if the user has set one, or the global config one
	 * @return mixed
	 */
	public static function getTimeZone() {
		$timeZone = '';
		$userTz = JFactory::getUser()->getParam('timezone');
		if ($userTz) {
			$timeZone = $userTz;
		} else {
			$timeZone = JFactory::getConfig()->get('offset');
		}
		return new DateTimeZone($timeZone);
	}

	public static function startsWith($haystack, $needle)
	{
		return $needle === "" || strpos($haystack, $needle) === 0;
	}
	public static function endsWith($haystack, $needle)
	{
		return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
	}
	
}