<?php
namespace HITScoutingNL\Plugin\Content\KampInfo\Extension;

// No direct access
defined('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Event\Content\ContentPrepareEvent;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Registry\Registry;
use Joomla\Event\SubscriberInterface;
use Psr\Container\ContainerInterface;

use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoUrlHelper;
use HITScoutingNL\Component\KampInfo\Administrator\Helper\KampInfoHelper;
use HITScoutingNL\Component\KampInfo\Administrator\Service\HTML\Icoon;
use HITScoutingNL\Component\KampInfo\Administrator\Service\HTML\Kamp;

final class Kampinfo extends CMSPlugin implements
    SubscriberInterface,
    BootableExtensionInterface
{
    use DatabaseAwareTrait;
    use HTMLRegistryAwareTrait;

    public static function getSubscribedEvents(): array {
        return [
            'onContentPrepare' => 'onContentPrepare',
        ];
    }

    public function boot(ContainerInterface $container) {
        $this->getRegistry()->register('kamp', new Kamp());
        $this->getRegistry()->register('icoon', new Icoon());
    }

    /*
     * Usage:
     * 
     * {kampinfo jaar="<jaartal>" type="landelijk|plaats" [plaats="$PLAATS"] [kopje=0|1] [volgorde="naam|leeftijd"] [delim="|"] [icons="0|1"] } 
     */
    public function onContentPrepare(ContentPrepareEvent $event) {
        $context = $event->getContext();
        $row     = $event->getItem();
        $params  = $event->getParams();

        if ($context === 'com_finder.indexer') {
            return;
        }

        $allowed_contexts = ['com_content.article', 'com_content.featured', 'com_content.category'];
        if (!in_array($context, $allowed_contexts, true)) {
            return;
        }

        if (!($params instanceof Registry)) {
            return;
        }

        if (!isset($row->id) || !(int) $row->id) {
            return;
        }

        $plugincode = 'kampinfo';
        $regex = "/{". $plugincode ."\ ([^}]+)\s*\}|{". $plugincode ."\s*\}/m";
        if (preg_match_all($regex, $row->text, $matches)) {

            $kampInfoConfig = ComponentHelper::getParams('com_kampinfo');
            $useComponentUrls = $kampInfoConfig->get('useComponentUrls') == 1;

            for ($i = 0; $i < count($matches[0]); $i++) {
                $config = [];
                $config['useComponentUrls'] = $useComponentUrls;
                
                // collect parameters
                $pluginParameters = explode(' ', $matches[1][$i]);
                foreach ($pluginParameters as $item) {
                    if ($item !== '') {
                        list($key, $value) = explode("=", $item);
                        $config[$key] = str_replace(["'",'"'], '', $value);
                    }
                }

                $type = '';
                if (array_key_exists('type', $config)) {
                    $type = $config['type'];
                }
                if ($type == '' || $type == 'landelijk') {
                    $result = $this->loadLandelijkOverzicht($config);
                    $row->text = str_replace($matches[0][$i], $result, $row->text);

                } else if ($type == 'plaats') {
                    $result = $this->loadPlaatsOverzicht($config);
                    $row->text = str_replace($matches[0][$i], $result, $row->text);
                }
            }
        }
    }

    private function getParamIfExists($config, $key) {
        if (array_key_exists($key, $config)) {
            return $config[$key];
        }
        return null;
    }

    private function loadLandelijkOverzicht($config) {
        if ($this->getParamIfExists($config, 'kopje') == '1') {
            $output .= "<h3>HIT ". $config['plaats'] .' '. $config['jaar'] ."</h3>";
        }

        $query = $this->createBaseQuery($config);
        $this->zetOpVolgorde($query, $config);

        $result = $this->loadObjectList($query);
        $output = "";
        foreach ($result as $row) {
            $output .= "<div class='kamp'>";
            $output .= $this->span('plaats', $row->plaats);
            $output .= $this->outputDelimiter($row, $config);
            $output .= $this->span('naam', $this->kampLink($row, $config));
            $output .= $this->outputDelimiter($row, $config);
            $output .= $this->span('leeftijd', $row->minl ."-". $row->maxl . " jaar");
            $output .= "</div>";
        }
        return $output;
    }

    private function loadPlaatsOverzicht($config) {
        $output = "";
        
        if ($this->getParamIfExists($config, 'kopje') == '1') {
            $output .= "<h3>HIT ". $config['plaats'] .' '. $config['jaar'] ."</h3>";
        }

        $query = $this->createBaseQuery($config);
        $query
            -> where('s.naam = :plaats')
            -> bind(':plaats', $config['plaats'])
        ;
        $this->zetOpVolgorde($query, $config);

        $result = $this->loadObjectList($query);
        foreach ($result as $row) {
            $output .= "<div class='kamp'>";
            $output .= $this->span('naam', $this->kampLink($row, $config));
            $output .= $this->outputDelimiter($row, $config);
            $output .= $this->span('leeftijd', $row->minl ."-". $row->maxl . " jaar");
            if ($this->getParamIfExists($config, 'icons') == 1) { 
                $output .= $this->outputDelimiter($row, $config);
                $output .= "<span class='icons'>" . HtmlHelper::_('kamp.icoontjes', $row, 'small') . "</span>";
            }
            if ($this->getParamIfExists($config, 'hitcourant')) { 
                $output .= $this->outputDelimiter($row, $config);
                $output .= $this->span('hitcourant', $row->hitcourant);
            }
            // $output .= $this->outputDelimiter($row, $config);
            // $output .= $this->span('foto', $row->foto);
            $output .= "</div>";
        }
        return $output;
    }

    private function outputDelimiter($row, $config) {
        $delim = $this->getParamIfExists($config, 'delim');
        if ($delim != null) {
            return $this->span('delim', '&nbsp;'.$delim.'&nbsp;');
        }
        return "";
    }

    private function kampLink($row, $config) {
        return "<a href='". KampInfoUrlHelper::activiteitURL($row->plaatsObj, $row->kampObj, $config['jaar'], $config['useComponentUrls'])."'>". $row->kamp ."</a>";
    }

    private function span($clazz, $contents) {
        return "<span class='". $clazz . "'>" . $contents . "</span>";
    }

    private function loadObjectList($query) {
        $iconList = $this->getIconenLijst();

        $db = $this->getDatabase();
        $db->setQuery($query);
        $result = $db->loadObjectList();
        foreach ($result as $row) {
            $row->plaatsObj = (object) [
                'id' => $row->plaatsId,
                'naam' => $row->plaats,
            ];
            $row->kampObj = (object) [
                'id' => $row->kampId,
                'naam' => $row->kamp,
            ];
            $ics = [];
            foreach (explode(',', $row->icoontjes) as $icon) {
                if (array_key_exists($icon, $iconList)) {
                    $ics[] = $iconList[$icon];
                }
            }
            $row->icoontjes = $ics;
        }
        return $result;
    }
     
    private function createBaseQuery($config) {
        $db    = $this->getDatabase();
        $query = $db->getQuery(true)
            -> select('p.jaar')
            -> select('s.id as plaatsId')
            -> select('s.naam as plaats')
            -> select('c.id as kampId')
            -> select('c.naam as kamp')
            -> select('c.minimumLeeftijd as minl, c.maximumLeeftijd as maxl')
            -> select('c.icoontjes')
            -> select('c.hitCourantTekst as hitcourant')
            -> select('c.webadresFoto1 as foto')

            -> select('c.gereserveerd')
            -> select('c.maximumAantalDeelnemers')
            -> select('c.aantalDeelnemers')
            -> select('c.maximumAantalSubgroepjes')

            -> from($db->quoteName('#__kampinfo_hitcamp', 'c'))
            -> join('LEFT', $db->quoteName('#__kampinfo_hitsite', 's').' ON s.id = c.hitsite_id')
            -> join('LEFT', $db->quoteName('#__kampinfo_hitproject', 'p').' ON p.id = s.hitproject_id')
        ;
        if ($this->getParamIfExists($config, 'skipAkkoord') == null) {
            $query
                -> where('c.akkoordHitKamp = 1')
                -> where('c.akkoordHitPlaats = 1')
                -> where('c.geannuleerd <> 1')
            ;
        }

        $jaar = $this->getParamIfExists($config, 'jaar');
        if ($jaar != null) {
            $query
                -> where('p.jaar = :jaar')
                -> bind(':jaar', $jaar)
            ;
        }
        return $query; 
    }

    private function zetOpVolgorde(&$query, $config) {
        if (array_key_exists('volgorde', $config)) {
            $volgordes = explode(',', $config['volgorde']);
            foreach ($volgordes as $volgorde) {
                if ($volgorde == 'naam') {
                    $query
                        -> order('c.naam ASC')
                    ;
                } elseif ($volgorde == 'leeftijd') {
                    $query
                        -> order('c.minimumLeeftijd ASC')
                        -> order('c.maximumLeeftijd ASC')
                        -> order('c.naam ASC')
                    ;
                } elseif ($volgorde == 'plaats') {
                    $query
                        -> order('s.naam ASC')
                    ;
                }
            }
        }
        return $query;
    }

    private function getIconenLijst() {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            -> select('i.bestandsnaam, i.tekst, i.volgorde, i.soort')
            -> from('#__kampinfo_hiticon i')
            -> order('i.bestandsnaam');

        try {
            $db->setQuery($query);
            $icons = $db->loadObjectList();
            
            $result = [];
            foreach ($icons as $icon) {
                $result[$icon->bestandsnaam] = (object) [
                    'bestandsnaam' => $icon->bestandsnaam,
                    'tekst' => $icon->tekst,
                    'volgorde' => $icon->volgorde,
                    'soort' => $icon->soort
                    ];
            }
            return $result;
        } catch (\RuntimeException $e) {
            throw new GenericDataException($e->getMessage(), 500);
        }
    }

}
?>
