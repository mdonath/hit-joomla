<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Helper;

use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Table\MenuType;
use Joomla\CMS\Table\Menu;
use Joomla\Database\ParameterType;


class MenuHelper {

    private $db;

    function __construct($db) {
        $this->db = $db;
    }

    private function getDatabase() {
        return $this->db;
    }

    public function createPlaatsMenu($sitemenu, $component, $siteId) {
        $db = $this->getDatabase();
        $plaatsData = $this->haalPlaatsData($siteId);

        // Controleer of het menutype voor de plaats al bestaat en zo niet, dan aanmaken
        $menuType = $this->createMenuTypeForSiteIfNotExists($plaatsData->naam);

        // Controleer of het menu-item "HIT's in xxx" al bestaat en zo ja, alles weggooien (!)
        $hitsInPlaatsMenuItemAlias = "hits-in-{$menuType}-{$plaatsData->jaar}";
        $hitsInPlaatsMenuItem = $sitemenu->getItems(['menutype', 'alias'], [$menuType, $hitsInPlaatsMenuItemAlias], true);
        if (!empty($hitsInPlaatsMenuItem)) {
            $oldMenu = new Menu($db);
            if (!$oldMenu->delete($hitsInPlaatsMenuItem->id, true)) {
                throw new \Exception($oldMenu->getError());
            }
        }

        // Maak (opnieuw) het "HIT's in xxx' menu-item
        $data = [
            'menutype' => $menuType,
            'title' => "HIT's in {$plaatsData->naam}",
            'alias' => $hitsInPlaatsMenuItemAlias,
            'path' => $$hitsInPlaatsMenuItemAlias,
            'type' => 'url',
            'link'=> '#',
            'note' => "HIT {$plaatsData->jaar}",
            'img' => ' ',
            'params' => '{ }',
            'language' => '*',
            'published' => 1,
            'level' => 1,
            'parent_id' => 1,
            'location' => 'last-child',
        ];
        $newMenu = $this->createNewMenu($data);
        $parentId = $newMenu->id;

        // Maak onder het "HIT's in xxx"-menuitem een menuitem 'Overzicht' met link naar overzicht van plaats
        $overzichtLink = "index.php?option=com_kampinfo&view=overzichtplaats&hitsite_id={$siteId}";
        $data = [
            'menutype' => $menuType,
            'title' => 'Overzicht',
            'alias' => 'overzicht',
            'path' => $hitsInPlaatsMenuItemAlias .'/overzicht',
            'type' => 'component',
            'component_id' => $component->id,
            'link'=> $overzichtLink,
            'img' => ' ',
            'params' => '{ }',
            'published' => 1,
            'level' => 2,
            'language' => '*',
            'parent_id' => $parentId,
            'location' => 'first-child',
        ];
        $this->createNewMenu($data);

        // Maak onder het "HIT's in xxx"-menuitem voor elk kamponderdeel een menuitem aan
        $kampen = $this->haalKampenData($siteId);
        foreach ($kampen as $kamp) {
            $alias = OutputFilter::stringURLSafe($kamp->naam);
            $link = "index.php?option=com_kampinfo&view=activiteit&hitcamp_id={$kamp->id}";
            $data = [
                'menutype' => $menuType,
                'title' => $kamp->naam,
                'alias' => $alias,
                'path' => $hitsInPlaatsMenuItemAlias .'/'. $alias,
                'type' => 'component',
                'component_id' => $component->id,
                'link'=> $link,
                'img' => ' ',
                'params' => '{ }',
                'published' => 1,
                'level' => 2,
                'language' => '*',
                'parent_id' => $parentId,
                'location' => 'last-child',
            ];
            $this->createNewMenu($data);
        }
    }

    /**
     * Haalt de naam en het jaar van de HIT plaats op.
     * 
     * @param mixed $siteId id van de HIT plaats
     * @return mixed object met naam en jaar van de plaats met opgegeven siteId
     */
    private function haalPlaatsData($siteId): mixed {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select($db->quoteName([
                's.naam',
                'p.jaar',
            ]))
            ->from($db->quoteName('#__kampinfo_hitsite', 's'))
            ->join('LEFT',
                $db->quoteName('#__kampinfo_hitproject', 'p'),
                $db->quoteName('s.hitproject_id') .' = '. $db->quoteName('p.id')
            )
            ->where($db->quoteName('s.id') . ' = :siteId')
            ->bind(':siteId', $siteId, ParameterType::INTEGER);
        $db->setQuery($query);

        return $db->loadObject();
    }

    /**
     * Haalt het id en de naam van elk kamponderdeel in opgegeven HIT Plaats op, gesorteerd op naam.
     * 
     * @param mixed $siteId id van de plaats
     * @return mixed lijst met id en naam van elk kamp
     */
    private function haalKampenData($siteId): mixed {
        $db = $this->getDatabase();

        $query = $db->getQuery(true)
            ->select($db->quoteName([
                'c.id',
                'c.naam',
            ]))
            ->from($db->quoteName('#__kampinfo_hitcamp', 'c'))
            ->where($db->quoteName('hitsite_id') . ' = :siteId')
            ->bind(':siteId', $siteId, ParameterType::INTEGER)
            ->order($db->quoteName('c.naam'));
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    /**
     * Maakt een MenuType voor de plaats met opgegeven naam.
     * 
     * @param mixed $naam
     * @return string de naam van het menutype (= lowercase naam)
     */
    private function createMenuTypeForSiteIfNotExists($naam): string {
        $db = $this->getDatabase();

        $menuType = strtolower($naam);

        $query = $db->getQuery(true)
            ->select('COUNT(id)')
            ->from($db->quoteName('#__menu_types'))
            ->where($db->quoteName('menutype') . ' = :menutype')
            ->bind(':menutype', $menuType, ParameterType::STRING);
        $db->setQuery($query);

        if (!$db->loadResult()) {
            // Aanmaken menutype voor plaats
            $data = [
                'menutype' => $menuType,
                'title' => $naam,
                'description' => "HIT {$naam}",
            ];
            $this->createNewMenuType($data);
        } else {
            // Bestaat al en dat is prima
        }
        
        return $menuType;
    }

    /**
     * Maakt een nieuw menutype aan. Elke plaats heeft zijn eigen menutype.
     * 
     * @param array $data data voor het nieuwe menutype
     * @throws \Exception
     * @return MenuType
     */
    private function createNewMenuType(array $data): MenuType {
        $db = $this->getDatabase();

        $result = new MenuType($db);

        if (!$result->bind($data)) {
            throw new \Exception($result->getError());
        }
        
        if (!$result->check()) {
            throw new \Exception($result->getError());
        }

        if (!$result->store()) {
            throw new \Exception($result->getError());
        }

        return $result;
    }

    /**
     * Maakt op basis van de data een menuitem aan onder de parentId op opgegeven position.
     * 
     * @param int $parentId onder welke parent het komt te hangen
     * @param string $position op welke plek onder de parent moet het worden toegevoegd ('before', 'after', 'first-child', 'last-child')
     * @param array $data data voor het nieuwe menu
     * @throws \Exception
     * @return Menu
     */
    private function createNewMenu(array $data): Menu {
        $db = $this->getDatabase();

        $parentId = $data["parent_id"];
        $location = $data["location"];

        $result = new Menu($db);

        if (!$result->bind($data, ['position'])) {
            throw new \Exception($result->getError());
        }

        // Hang 'm op de juiste plek in de boomstructuur (!!!)
        $result->setLocation($parentId, $location);

        if (!$result->check()) {
            throw new \Exception($result->getError());
        }

        if (!$result->store()) {
            throw new \Exception($result->getError());
        }

        return $result;
    }
}
