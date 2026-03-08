<?php
namespace HITScoutingNL\Plugin\Content\KampInfo\Extension;

// No direct access
defined('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Extension\BootableExtensionInterface;

use Joomla\Database\ParameterType;
use Psr\Container\ContainerInterface;

use HITScoutingNL\Component\KampInfo\Administrator\Service\HTML\Icoon;
use HITScoutingNL\Component\KampInfo\Administrator\Service\HTML\Kamp;
use HITScoutingNL\Library\KampInfo\Icoon\IcoonUtil;
use HITScoutingNL\Library\KampInfo\Helper\KampInfoUrlHelper;
use HITScoutingNL\Library\KampInfo\ContentPlugin\AbstractContentPlugin;


final class Kampinfo extends AbstractContentPlugin implements BootableExtensionInterface
{
    private const PLUGIN_CODE = 'kampinfo';

    protected function getPluginName() {
        return static::PLUGIN_CODE;
    }

    protected function getAllowedContexts() {
        return ['com_content.article', 'com_content.featured', 'com_content.category'];
    }

    public function boot(ContainerInterface $container) {
        $this->getRegistry()->register('kamp', new Kamp(), true);
        $this->getRegistry()->register('icoon', new Icoon(), true);
    }

    /*
     * Usage:
     * 
     * {kampinfo jaar="<jaartal>" type="landelijk|plaats" [plaats="$PLAATS"] [kopje=0|1] [volgorde="naam|leeftijd"] [delim="|"] [icons="0|1"]} 
     */
    protected function renderPlugin($pluginParameters) {
        $config = $pluginParameters;
        $config['useComponentUrls'] = $this->getKampInfoConfig()->get('useComponentUrls') == 1;

        $type = '';
        if (array_key_exists('type', $config)) {
            $type = $config['type'];
        }
        if ($type == '' || $type == 'landelijk') {
            $html = $this->loadLandelijkOverzicht($config);

        } else if ($type == 'plaats') {
            $html = $this->loadPlaatsOverzicht($config);
        }
        return $html;
    }

    private function loadLandelijkOverzicht($config) {
        $query = $this->createBaseQuery($config);
        $this->zetOpVolgorde($query, $config);
        $result = $this->haalHitKampen($query);

        $variables = [];
        $variables['result'] = $result;
        $variables['config'] = $config;

        return $this->renderTemplate(
            static::PLUGIN_CODE,
            'landelijk',
            [
                'config' => $config,
                'result' => $result
            ]
        );
    }

    private function loadPlaatsOverzicht($config) {
        $db = $this->getDatabase();
        $query = $this->createBaseQuery($config);
        $query
            ->where($db->quoteName('s.naam') . ' = :plaats')
            ->bind(':plaats', $config['plaats'])
        ;
        $this->zetOpVolgorde($query, $config);

        $result = $this->haalHitKampen($query);

        return $this->renderTemplate(
            static::PLUGIN_CODE,
            'plaats',
            [
                'config' => $config,
                'result' => $result
            ]
        );
    }

    public function outputDelimiter($config) {
        $delim = $this->getParamIfExists($config, 'delim');
        if ($delim == null) {
            return "";
        }
        return "<span class='delim'>{$delim}</span>";
    }

    public function kampLink($row, $config) {
        return "<a href='". KampInfoUrlHelper::activiteitURL($row->plaatsObj, $row->kampObj, $config['jaar'], $config['useComponentUrls'])."'>". $row->kamp ."</a>";
    }

    private function haalHitKampen($query) {
        $db = $this->getDatabase();

        // Het is efficiënter om de lijst 1x op te halen en te cachen, dan voor elk kamp een nieuwe query te doen.
        $iconenMap = IcoonUtil::getIconenMap($db);

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
            $row->icoontjes = IcoonUtil::explodeIcoontjes($row, $iconenMap);
        }
        return $result;
    }
     
    private function createBaseQuery($config) {
        $db    = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select([
                $db->quoteName('p.jaar'),
                $db->quoteName('s.id', 'plaatsId'),
                $db->quoteName('s.naam', 'plaats'),
                $db->quoteName('c.id', 'kampId'),
                $db->quoteName('c.naam', 'kamp'),
                $db->quoteName('c.minimumLeeftijd', 'minl'),
                $db->quoteName('c.maximumLeeftijd', 'maxl'),
                $db->quoteName('c.icoontjes'),
                $db->quoteName('c.hitCourantTekst', 'hitcourant'),
                $db->quoteName('c.webadresFoto1', 'foto'),

                $db->quoteName('c.gereserveerd'),
                $db->quoteName('c.maximumAantalDeelnemers'),
                $db->quoteName('c.aantalDeelnemers'),
                $db->quoteName('c.maximumAantalSubgroepjes'),
                $db->quoteName('c.aantalSubgroepen'),
                // Nodig voor automagische icoontjes
                $db->quoteName('c.startDatumTijd'),
                $db->quoteName('c.eindDatumTijd'),
                $db->quoteName('c.isouderkind'),
                // Nodig voor vol/loterij icoon
                $db->quoteName('p.inschrijvingStartdatum', 'startInschrijving'),
                $db->quoteName('p.loterijStartdatum', 'startLoterij'),
                $db->quoteName('p.loterijEinddatum', 'eindLoterij'),
            ])
            ->from($db->quoteName('#__kampinfo_hitcamp', 'c'))
            ->join('LEFT',
                $db->quoteName('#__kampinfo_hitsite', 's'),
                $db->quoteName('s.id') . ' = '. $db->quoteName('c.hitsite_id')
            )
            ->join('LEFT',
                $db->quoteName('#__kampinfo_hitproject', 'p'),
                $db->quoteName('p.id') .' = '. $db->quoteName('s.hitproject_id')
            )
        ;

        if ($this->getParamIfExists($config, 'skipAkkoord') == null) {
            $query
                ->where([
                    $db->quoteName('c.akkoordHitKamp') . ' = 1',
                    $db->quoteName('c.akkoordHitPlaats') . ' = 1',
                    $db->quoteName('c.geannuleerd') . ' <> 1',
                ])
            ;
        }

        $jaar = $this->getParamIfExists($config, 'jaar');
        if ($jaar != null) {
            $jaar = (int) $jaar;
            $query
                ->where($db->quoteName('p.jaar') . ' = :jaar')
                ->bind(':jaar', $jaar, ParameterType::INTEGER)
            ;
        }
        return $query; 
    }

    private function zetOpVolgorde(&$query, $config) {
        $db = $this->getDatabase();
        if (array_key_exists('volgorde', $config)) {
            $volgordes = explode(',', $config['volgorde']);
            foreach ($volgordes as $volgorde) {
                if ($volgorde === 'naam') {
                    $query->order($db->quoteName('c.naam') . ' ASC');
                } elseif ($volgorde == 'leeftijd') {
                    $query
                        ->order($db->quoteName('c.minimumLeeftijd') . ' ASC')
                        ->order($db->quoteName('c.maximumLeeftijd') . ' ASC')
                        ->order($db->quoteName('c.naam') . ' ASC')
                    ;
                } elseif ($volgorde == 'plaats') {
                    $query->order($db->quoteName('s.naam') . ' ASC');
                }
            }
        }
        return $query;
    }

}
