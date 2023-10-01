<?php
// No direct access
defined('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Plugin\CMSPlugin;

require_once JPATH_COMPONENT_ADMINISTRATOR.'/../com_kampinfo/helpers/kampinfourl.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/../com_kampinfo/helpers/kampinfo.php';


class PlgContentKampinfo extends CMSPlugin {

    protected $db;

	protected $autoloadLanguage = true;

    /*
     * Usage:
     * 
     * {kampinfo [type="landelijk|plaats"] [plaats="$PLAATS"] [kopje=0|1] [volgorde="naam|leeftijd"]} 
     */

	function onContentPrepare($context, $article, $params, $limitstart) {
        $allowed_contexts = array('com_content.article', 'com_content.featured', 'com_content.category');
        
		if (!in_array($context, $allowed_contexts, true)) {
			return true;
		}

		if (!isset($article->id) || !(int) $article->id) {
			return true;
		}

		$plugincode = 'kampinfo';
		$regex = "/{". $plugincode ."\ ([^}]+)\}|{". $plugincode ."\}/m";
		if (preg_match_all($regex, $article->text, $matches)) {

            $params = ComponentHelper::getParams('com_kampinfo');

            for ($i = 0; $i < count($matches[0]); $i++) {

				$configs = explode(' ', $matches[1][$i]);
                $config = array();
                $config['useComponentUrls'] = $params->get('useComponentUrls') == 1;
                $config['iconFolderSmall'] = $params->get('iconFolderSmall');
                $config['iconExtension'] = $params->get('iconExtension');
                
                // collect parameters
				foreach ($configs as $item) {
					list($key, $value) = explode("=", $item);
					$config[$key] = str_replace(array("'",'"'), '', $value);
				}

				$type = '';
				if (array_key_exists('type', $config)) {
					$type = $config['type'];
				}
				if ($type == '' || $type == 'landelijk') {
					$result = $this->load_landelijk_overzicht($config);
					$article->text = str_replace($matches[0][$i], $result, $article->text);

				} else if ($type == 'plaats') {
					$result = $this->load_plaats_overzicht($config);
					$article->text = str_replace($matches[0][$i], $result, $article->text);
				}
			}
		}
		return true;
	}

    function getParamIfExists($config, $key) {
        if (array_key_exists($key, $config)) {
            return $config[$key];
        }
        return null;
    }

    function load_landelijk_overzicht($config) {
        if ($this->getParamIfExists($config, 'kopje') == "1") {
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

    function load_plaats_overzicht($config) {
        $output = "";
        
        if ($this->getParamIfExists($config, 'kopje') == "1") {
            $output .= "<h3>HIT ". $config['plaats'] .' '. $config['jaar'] ."</h3>";
        }

        $db = $this->db;
        $query = $this->createBaseQuery($config);
        $query->where('s.naam = '. $db->quote($db->escape($config['plaats'])));
        $this->zetOpVolgorde($query, $config);

        $result = $this->loadObjectList($query);
        foreach ($result as $row) {
			$output .= "<div class='kamp'>";
            $output .= $this->span('naam', $this->kampLink($row, $config));
            $output .= $this->outputDelimiter($row, $config);
			$output .= $this->span('leeftijd', $row->minl ."-". $row->maxl . " jaar");
            if ($this->getParamIfExists($config, 'icons')) { 
                $output .= $this->outputDelimiter($row, $config);
                $output .= "<span class='icons'>";
                foreach ($row->iconen as $icoon) {
                    $output .= (KampInfoUrlHelper::imgUrl($config['iconFolderSmall'], $icoon->naam, $icoon->tekst, $config['iconExtension']));
                }
                $output .= "</span>";
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

    function outputDelimiter($row, $config) {
        $delim = $this->getParamIfExists($config, 'delim');
        if ($delim != null) {
            return $this->span('delim', '&nbsp;'.$delim.'&nbsp;');
        }
        return "";
    }

    function kampLink($row, $config) {
        return "<a href='". KampInfoUrlHelper::activiteitURL($row->plaatsObj, $row->kampObj, $config['jaar'], $config['useComponentUrls'])."'>". $row->kamp ."</a>";
    }

    function span($clazz, $contents) {
        return "<span class='". $clazz . "'>" . $contents . "</span>";
    }

    function loadObjectList($query) {
        $iconList = $this->getIconenLijst();

        $db = $this->db;
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
            $ics = array();
            foreach (explode(',', $row->icoontjes) as $icon) {
                if (array_key_exists($icon, $iconList)) {
                    $ics[] = $iconList[$icon];
                }
            }
            $row->iconen = $ics;
        }
        return $result;
    }
     
    function createBaseQuery($config) {
        $db = $this->db;
        $query = $db->getQuery(true)
            ->select('p.jaar')
            ->select('s.id as plaatsId')
            ->select('s.naam as plaats')
            ->select('c.id as kampId')
            ->select('c.naam as kamp')
            ->select('c.minimumLeeftijd as minl, c.maximumLeeftijd as maxl')
            ->select('c.icoontjes')
            ->select('c.hitCourantTekst as hitcourant')
            ->select('c.webadresFoto1 as foto')
		    ->from($db->quoteName('#__kampinfo_hitcamp', 'c'))
		    ->join('LEFT', $db->quoteName('#__kampinfo_hitsite', 's').' ON s.id=c.hitsite_id')
            ->join('LEFT', $db->quoteName('#__kampinfo_hitproject', 'p').' ON p.id=s.hitproject_id')
        ;
        if ($this->getParamIfExists($config, 'skipAkkoord') == null) {
            $query->where('(c.akkoordHitKamp=1 and c.akkoordHitPlaats=1)');
        }

        if ($this->getParamIfExists($config, 'jaar') != null) {
            $query->where('p.jaar = '. ((int)$db->escape($config['jaar'])));
        }
		return $query; 
    }

    function zetOpVolgorde(&$query, $config) {
        if (array_key_exists('volgorde', $config)) {
            $volgordes = explode(',', $config['volgorde']);
            foreach ($volgordes as $volgorde) {
                if ($volgorde == 'naam') {
                    $query->order('c.naam ASC');
                } elseif ($volgorde == 'leeftijd') {
                    $query->order('c.minimumLeeftijd ASC');
                    $query->order('c.maximumLeeftijd ASC');
                    $query->order('c.naam ASC');
                } elseif ($volgorde == 'plaats') {
                    $query->order('s.naam ASC');
                }
            }
        }
        return $query;
    }

    public function getIconenLijst() {
		$db = $this->db;

		$query = $db->getQuery(true)
		    -> select('i.bestandsnaam as naam, i.tekst, i.volgorde, i.soort')
            -> from('#__kampinfo_hiticon i')
            -> order('i.bestandsnaam');

		$db->setQuery($query);
        try {
    		$icons = $db->loadObjectList();
            
            $result = array();
            foreach ($icons as $icon) {
                $result[$icon->naam] = (object) [
                    'naam' => $icon->naam,
                    'tekst' => $icon->tekst,
                    'volgorde' => $icon->volgorde,
                    'soort' => $icon->soort
                    ];
            }
            return $result;
        } catch (\RuntimeException $e) {
            JError::raiseWarning(500, $e);
        }
	}
}
?>
