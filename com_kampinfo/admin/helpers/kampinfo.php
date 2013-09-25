<?php

/**
 * KampInfo component helper.
 */
abstract class KampInfoHelper {

	/**
	 * 
	 * @param unknown $jaar
	 * @return De dat
	 */
	public static function eersteHitDag($jaar) { // VRUDAG DUS
		$paasKalender = array(
				2008 => DateTime::createFromFormat('d-m-Y', '21-03-2008'),
				2009 => DateTime::createFromFormat('d-m-Y', '10-04-2009'),
				2010 => DateTime::createFromFormat('d-m-Y', '02-04-2010'),
				2011 => DateTime::createFromFormat('d-m-Y', '22-04-2011'),
				2012 => DateTime::createFromFormat('d-m-Y', '06-04-2012'),
				2013 => DateTime::createFromFormat('d-m-Y', '29-03-2013'),
				2013 => DateTime::createFromFormat('d-m-Y', '18-04-2014'),
				2014 => DateTime::createFromFormat('d-m-Y', '03-04-2015'),
				2015 => DateTime::createFromFormat('d-m-Y', '25-03-2016'),
				2016 => DateTime::createFromFormat('d-m-Y', '14-04-2017'),
				2017 => DateTime::createFromFormat('d-m-Y', '30-03-2018'),
				2018 => DateTime::createFromFormat('d-m-Y', '19-04-2019'),
				2019 => DateTime::createFromFormat('d-m-Y', '10-04-2020'),
				2020 => DateTime::createFromFormat('d-m-Y', '02-04-2021'),
		);
		return $paasKalender[$jaar];
	}

	public static function addSubmenu($submenu) {
		JSubMenuHelper :: addEntry(JText :: _('COM_KAMPINFO_SUBMENU_HITPROJECTS'), 'index.php?option=com_kampinfo&view=hitprojects', $submenu == 'hitprojects');
		JSubMenuHelper :: addEntry(JText :: _('COM_KAMPINFO_SUBMENU_HITSITES'), 'index.php?option=com_kampinfo&view=hitsites', $submenu == 'hitsites');
		JSubMenuHelper :: addEntry(JText :: _('COM_KAMPINFO_SUBMENU_HITCAMPS'), 'index.php?option=com_kampinfo&view=hitcamps', $submenu == 'hitcamps');
		JSubMenuHelper :: addEntry(JText :: _('COM_KAMPINFO_SUBMENU_HITICONS'), 'index.php?option=com_kampinfo&view=hiticons', $submenu == 'hiticons');
		JSubMenuHelper :: addEntry(JText :: _('COM_KAMPINFO_SUBMENU_IMPORT'), 'index.php?option=com_kampinfo&view=import', $submenu == 'import');
		JSubMenuHelper :: addEntry(JText :: _('COM_KAMPINFO_SUBMENU_DOWNLOADS'), 'index.php?option=com_kampinfo&view=downloads', $submenu == 'downloads');
		JSubMenuHelper :: addEntry(JText :: _('COM_KAMPINFO_SUBMENU_INFO'), 'index.php?option=com_kampinfo&view=info', $submenu == 'info');
		
		// set some global property
		$document = JFactory :: getDocument();
		$document->addStyleDeclaration('.icon-48-kampinfo ' . '{background-image: url(../media/com_kampinfo/images/kampinfo-48x48.png);}');

		if ($submenu == 'hitprojects') {
			$document->setTitle(JText :: _('COM_KAMPINFO_HITPROJECTS_DOCTITLE'));
		}
		elseif ($submenu == 'hitsites') {
			$document->setTitle(JText :: _('COM_KAMPINFO_HITSITES_DOCTITLE'));
		}
		elseif ($submenu == 'hitcamps') {
			$document->setTitle(JText :: _('COM_KAMPINFO_HITCAMPS_DOCTITLE'));
		}
		elseif ($submenu == 'hiticons') {
			$document->setTitle(JText :: _('COM_KAMPINFO_HITICONS_DOCTITLE'));
		}
		elseif ($submenu == 'import') {
			$document->setTitle(JText :: _('COM_KAMPINFO_IMPORT_DOCTITLE'));
		}
		elseif ($submenu == 'downloads') {
			$document->setTitle(JText :: _('COM_KAMPINFO_DOWNLOADS_DOCTITLE'));
		}
		elseif ($submenu == 'info') {
			$document->setTitle(JText :: _('COM_KAMPINFO_INFO_DOCTITLE'));
		}
	}

	public static function getHitActiviteitOptions() {
		$options = array ();

		$db = JFactory :: getDbo();
		$query = $db->getQuery(true);

		$query->select('c.id As value, concat(c.naam, " (", s.naam, " - ", s.jaar, ")") As text');
		$query->from('#__kampinfo_hitcamp c');

		$query->select('s.naam as plaats, s.id as hitsite_id');
		$query->join('LEFT', '#__kampinfo_hitsite AS s ON c.hitsite=s.code');

		$query->order('s.jaar, s.naam, c.naam');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}

	public static function getDeelnemersnummerOptions() {
		$options = array ();

		$db = JFactory :: getDbo();
		$query = $db->getQuery(true);

		$query->select('c.deelnemersnummer As value, concat(s.jaar, " - ", s.naam, " - ", c.naam, " (", c.deelnemersnummer, ")") As text');
		$query->from('#__kampinfo_hitcamp c');

		$query->select('s.naam as plaats, s.id as hitsite_id');
		$query->join('LEFT', '#__kampinfo_hitsite AS s ON c.hitsite=s.code');

		$query->order('s.jaar, s.naam, c.naam');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}

	public static function getHitProjectOptions() {
		$options = array ();

		$db = JFactory :: getDbo();
		$query = $db->getQuery(true);

		$query->select('jaar As value, jaar As text');
		$query->from('#__kampinfo_hitproject');
		$query->order('jaar');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}

	public static function getHitSiteOptions() {
		$options = array ();

		$db = JFactory :: getDbo();
		$query = $db->getQuery(true);

		$query->select('code As value, concat(naam, " (", jaar,")") As text');
		$query->from('#__kampinfo_hitsite');
		$query->order('jaar, naam');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}

	public static function getSelectedHitIcons($deelnemersnummer) {
		$options = array ();

		$db = JFactory :: getDbo();
		$query = $db->getQuery(true);

		$query->select('bestandsnaam');
		$query->from('#__kampinfo_camp_icon');
		$query->where('deelnemersnummer = ' . $db->quote($db->getEscaped($deelnemersnummer)));
		$query->order('volgorde');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}
	public static function getHitIconOptions() {
		$options = array ();

		$db = JFactory :: getDbo();
		$query = $db->getQuery(true);

		$query->select('bestandsnaam As value, tekst As text');
		$query->from('#__kampinfo_hiticon');
		$query->order('volgorde');

		// Get the options.
		$db->setQuery($query);

		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError :: raiseWarning(500, $db->getErrorMsg());
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
}