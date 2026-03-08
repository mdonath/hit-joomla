<?php
namespace HITScoutingNL\Plugin\Content\SocialMedia\Extension;

// No direct access
defined('_JEXEC') or die ('Restricted access');


use Joomla\Database\ParameterType;

use HITScoutingNL\Library\KampInfo\ContentPlugin\AbstractContentPlugin;


final class Socialmedia extends AbstractContentPlugin {

    private const PLUGIN_CODE = 'socialmedia';

    protected function getPluginName() {
        return static::PLUGIN_CODE;
    }

    protected function getAllowedContexts() {
        return ['com_content.article', 'com_content.featured', 'com_content.category'];
    }

    /*
     * Usage:
     * 
     * {socialmedia [jaar="<jaartal>"] plaats="<Alphen|Dwingeloo|Harderwijk|Heerenveen|Nijmegen|Ommen|Zandvoort|Zeeland>" } 
     */
    protected function renderPlugin($pluginParameters) {
        $config = $pluginParameters;
        $config['projectId'] = $this->getKampInfoConfig()->get('huidigeActieveJaar');

        // Indien geen jaar, val dan terug op huidige actieve jaar
        $jaar = $this->getParamIfExists($config, 'jaar');
        if ($jaar != null) {
            $config['jaar'] = $jaar;
        }

        $html = $this->genereerSocialMediaOverzicht($config);

        return $html;
    }

    private function genereerSocialMediaOverzicht($config) {
        $this->loadLanguage();

        $result = $this->getData($config);

        return $this->renderTemplate(
            static::PLUGIN_CODE,
            'icons',
            [
                'config' => $config,
                'result' => $result,
            ]
        );
    }

    private function getData($config) {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select([
                $db->quoteName('p.jaar'),
                $db->quoteName('s.naam', 'plaats'),
                $db->quoteName('s.socialmediaFacebook', 'facebook'),
                $db->quoteName('s.socialmediaInstagram', 'instagram'),
            ])
            ->from($db->quoteName('#__kampinfo_hitsite', 's'))
            ->join('LEFT',
                $db->quoteName('#__kampinfo_hitproject', 'p'),
                $db->quoteName('p.id') .' = '. $db->quoteName('s.hitproject_id')
            )
            ->where($db->quoteName('s.naam') . ' = :plaats')
            ->bind(':plaats', $config['plaats'])
        ;

        if (!array_key_exists('jaar', $config)) {
            $projectId = (int) $config['projectId'];
            $query
                ->where($db->quoteName('p.id') . ' = :projectId')
                ->bind(':projectId', $projectId, ParameterType::INTEGER)
            ;
        } else {
            $jaar = (int) $config['jaar'];
            $query
                ->where($db->quoteName('p.jaar') . ' = :jaar')
                ->bind(':jaar', $jaar, ParameterType::INTEGER)
            ;
        }

        $db->setQuery($query);
        $result = $db->loadObject();
        return $result;
    }

}
