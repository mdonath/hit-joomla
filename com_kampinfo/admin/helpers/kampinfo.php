<?php

// No direct access to this file
defined('_JEXEC') or die;

/**
 * KampInfo component helper.
 */
abstract class KampInfoHelper {

	public static function addSubmenu($submenu) {
		JSubMenuHelper :: addEntry(JText :: _('COM_KAMPINFO_SUBMENU_HITPROJECTS'), 'index.php?option=com_kampinfo&view=hitprojects', $submenu == 'hitprojects');
		JSubMenuHelper :: addEntry(JText :: _('COM_KAMPINFO_SUBMENU_HITSITES'), 'index.php?option=com_kampinfo&view=hitsites', $submenu == 'hitsites');
		JSubMenuHelper :: addEntry(JText :: _('COM_KAMPINFO_SUBMENU_HITCAMPS'), 'index.php?option=com_kampinfo&view=hitcamps', $submenu == 'hitcamps');
		// set some global property
		$document = JFactory :: getDocument();
		$document->addStyleDeclaration('.icon-48-kampinfo ' . '{background-image: url(../media/com_kampinfo/images/kampinfo-48x48.png);}');

		if ($submenu == 'hitprojects') {
			$document->setTitle(JText :: _('COM_KAMPINFO_HITPROJECTS_DOCTITLE'));
		} elseif ($submenu == 'hitsites') {
			$document->setTitle(JText :: _('COM_KAMPINFO_HITSITES_DOCTITLE'));
		} elseif ($submenu == 'hitcamps') {
				$document->setTitle(JText :: _('COM_KAMPINFO_HITCAMPS_DOCTITLE'));
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
}