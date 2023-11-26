<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Helper;

\defined('_JEXEC') or die('Restricted access');

use DateTimeZone;
use Joomla\CMS\Access\Access;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Object\CMSObject;

/**
 * KampInfo component helper.
 */
class KampInfoHelper {

    /**
     * Get the actions
     */
    public static function getActions($entity = 'component', $entityIds = null) {
        $user = Factory::getUser();
        $actions = Access::getActionsFromFile(JPATH_ADMINISTRATOR . '/components/com_kampinfo/access.xml', "/access/section[@name='" . $entity . "']/");
        
        $result = new CMSObject();
        if (empty($entityIds)) {
            foreach ($actions as $action) {
                $result->set($action->name, $user->authorise($action->name, 'com_kampinfo'));
            }
        } else {
            foreach ($actions as $action) {
                $result->set($action->name, $user->authorise($action->name, 'com_kampinfo'));
                if (self::startsWith($action->name, "hit$entity") && !self::endsWith($action->name, 'create')) {
                    foreach ($entityIds as $entityId) {
                        $isAuth = $user->authorise($action->name, "com_kampinfo.$entity.$entityId");
                        $result->set("$action->name.$entityId", $isAuth);
                        if (!empty($isAuth)) {
                            $result->set($action->name, $isAuth);
                        }
                    }
                }
            }
        }

        echo("<h1>Entity: $entity</h1><ul>");
        foreach ($result as $k=>$v) {
            echo ("<li>Action: $k: $v</li>");
        }
        echo '</ul>';

        return $result;
    }

    /**
     * 
     * @param unknown $jaar
     * @return De dat
     */
    public static function eersteHitDag($jaar) { // VRIJDAG DUS
        $paasKalender = array(
                    2004 => '09-04-2004'
                , 2005 => '25-03-2005'
                , 2006 => '14-04-2006'
                , 2007 => '06-04-2007'
                , 2008 => '21-03-2008'
                , 2009 => '10-04-2009'
                , 2010 => '02-04-2010'
                , 2011 => '22-04-2011'
                , 2012 => '06-04-2012'
                , 2013 => '29-03-2013'
                , 2014 => '18-04-2014'
                , 2015 => '03-04-2015'
                , 2016 => '25-03-2016'
                , 2017 => '14-04-2017'
                , 2018 => '30-03-2018'
                , 2019 => '19-04-2019'
                , 2020 => '10-04-2020'
                , 2021 => '02-04-2021'
                , 2022 => '15-04-2022'
                , 2023 => '07-04-2023'
                , 2024 => '29-03-2024'
                , 2025 => '18-04-2025'
                , 2026 => '03-04-2026'
                , 2027 => '26-03-2027'
                , 2028 => '14-04-2028'
                , 2029 => '30-03-2029'
                , 2030 => '19-04-2030'
                , 2031 => '11-04-2031'
                , 2032 => '26-03-2032'
                , 2033 => '15-04-2033'
        );
        return DateTime::createFromFormat('d-m-Y', $paasKalender[$jaar]);
    }

    // TODO: WEGGOOIEN?
    public static function addSubmenu($submenu) {
        // set some global property
        $document = Factory::getDocument();
        $document->addStyleDeclaration('.icon-48-kampinfo ' . '{background-image: url(../media/com_kampinfo/images/kampinfo-48x48.png);}');

        // Show submenu items
        JHtmlSidebar::addEntry(Text::_('COM_KAMPINFO_SUBMENU_INFO'), 'index.php?option=com_kampinfo&view=info', $submenu == 'info');
        
        if (Factory::getUser()->authorise('hitproject.menu', 'com_kampinfo')) {
            JHtmlSidebar::addEntry(Text::_('COM_KAMPINFO_SUBMENU_HITPROJECTS'), 'index.php?option=com_kampinfo&view=hitprojects', $submenu == 'hitprojects');
        }
        if (Factory::getUser()->authorise('hitsite.menu', 'com_kampinfo')) {
            JHtmlSidebar::addEntry(Text::_('COM_KAMPINFO_SUBMENU_HITSITES'), 'index.php?option=com_kampinfo&view=hitsites', $submenu == 'hitsites');
        }
        if (Factory::getUser()->authorise('hitcamp.menu', 'com_kampinfo')) {
            JHtmlSidebar::addEntry(Text::_('COM_KAMPINFO_SUBMENU_HITCAMPS'), 'index.php?option=com_kampinfo&view=hitcamps', $submenu == 'hitcamps');
        }
        if (Factory::getUser()->authorise('core.admin', 'com_kampinfo')) {
            JHtmlSidebar::addEntry(Text::_('COM_KAMPINFO_SUBMENU_HITICONS'), 'index.php?option=com_kampinfo&view=hiticons', $submenu == 'hiticons');
            JHtmlSidebar::addEntry(Text::_('COM_KAMPINFO_SUBMENU_IMPORT'), 'index.php?option=com_kampinfo&view=import', $submenu == 'import');
            JHtmlSidebar::addEntry(Text::_('COM_KAMPINFO_SUBMENU_DOWNLOADS'), 'index.php?option=com_kampinfo&view=downloads', $submenu == 'downloads');
            JHtmlSidebar::addEntry(Text::_('COM_KAMPINFO_SUBMENU_REPORTS'), 'index.php?option=com_kampinfo&view=reports', $submenu == 'reports');
        }
                
        // Set the title
        if ($submenu == 'hitprojects') {
            $document->setTitle(Text::_('COM_KAMPINFO_HITPROJECTS_DOCTITLE'));
        }
        elseif ($submenu == 'hitsites') {
            $document->setTitle(Text::_('COM_KAMPINFO_HITSITES_DOCTITLE'));
        }
        elseif ($submenu == 'hitcamps') {
            $document->setTitle(Text::_('COM_KAMPINFO_HITCAMPS_DOCTITLE'));
        }
        elseif ($submenu == 'hiticons') {
            $document->setTitle(Text::_('COM_KAMPINFO_HITICONS_DOCTITLE'));
        }
        elseif ($submenu == 'import') {
            $document->setTitle(Text::_('COM_KAMPINFO_IMPORT_DOCTITLE'));
        }
        elseif ($submenu == 'downloads') {
            $document->setTitle(Text::_('COM_KAMPINFO_DOWNLOADS_DOCTITLE'));
        }
        elseif ($submenu == 'reports') {
            $document->setTitle("Overzichten");
        }
        elseif ($submenu == 'info') {
            $document->setTitle(Text::_('COM_KAMPINFO_INFO_DOCTITLE'));
        }
    }

    public static function getHitActiviteitOptions() {
        $options = array ();

        $db = Factory::getDbo();
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

        return $options;
    }


    public static function getHitProjectOptions() {
        $options = array ();

        $db = Factory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id As value, jaar As text');
        $query->from('#__kampinfo_hitproject');
        $query->order('jaar desc');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        return $options;
    }

    public static function getHitJaarOptions() {
        $options = array ();

        $db = Factory::getDbo();
        $query = $db->getQuery(true);

        $query->select('jaar As value, jaar As text');
        $query->from('#__kampinfo_hitproject');
        $query->order('jaar desc');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

        return $options;
    }

    public static function getHitSiteOptions($hitproject_id = null) {
        $options = array ();

        $db = Factory::getDbo();
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

        return $options;
    }

    public static function getHitIconOptions() {
        $options = array ();

        $db = Factory::getDbo();
        $query = $db->getQuery(true);

        $query->select('bestandsnaam As value, tekst As text, uitleg');
        $query->from('#__kampinfo_hiticon');
        $query->where('soort <> "S"');
        $query->order('volgorde');

        // Get the options.
        $db->setQuery($query);

        $options = $db->loadObjectList();

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
                "K" => "Koken",
                "S" => "Systeem"
        );
    }

    public static function getHitPrijzenOptions() {
        $params = ComponentHelper::getParams('com_kampinfo');
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


    public static function reverse($date, $metTijd=false) {
        if ($date != '0000-00-00') {
            $date = new Date($date);
            $date->setTimezone(self::getTimeZone());
            $format = 'd-m-Y';
            if ($metTijd) {
                $format .= ' H:i';
            }
            return $date->format($format, true);
        }
        return $date;
    }

    /**
     * Returns the userTime zone if the user has set one, or the global config one
     * @return mixed
     */
    public static function getTimeZone() {
        $timeZone = '';
        $userTz = Factory::getUser()->getParam('timezone');
        if ($userTz) {
            $timeZone = $userTz;
        } else {
            $timeZone = Factory::getConfig()->get('offset');
        }
        return new DateTimeZone($timeZone);
    }

    public static function aantalOvernachtingen($kamp) {
        $start = self::clearTime($kamp->startDatumTijd);
        $eind = self::clearTime($kamp->eindDatumTijd);
        return $start->diff($eind)->days;
    }

    private static function clearTime($datumTijd) {
        $datum = new Date($datumTijd);
        $datum->setTimezone(self::getTimezone());
        $datum->setTime(0,0,0);
        return $datum;
    }

    public static function startsWith($haystack, $needle) {
        return $needle === "" || strpos($haystack, $needle) === 0;
    }
    public static function endsWith($haystack, $needle) {
        return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
    }

}