<?php
namespace HITScoutingNL\Plugin\Content\KampInfoAantallen\Extension;

// No direct access
defined('_JEXEC') or die ('Restricted access');

use Joomla\Database\ParameterType;

use HITScoutingNL\Library\KampInfo\ContentPlugin\AbstractContentPlugin;


final class KampinfoAantallen extends AbstractContentPlugin {
    private const PLUGIN_CODE = 'kampinfoaantallen';

    protected function getPluginName() {
        return static::PLUGIN_CODE;
    }

    protected function getAllowedContexts() {
        return [
            'com_content.article',
            'com_content.featured',
            'com_content.category',
            'mod_custom.content'
        ];
    }

    /*
     * Usage:
     * 
     * {kampinfoaantallen jaar="<jaartal>"}
     */
    protected function renderPlugin($pluginParameters) {
        $config = $pluginParameters;

        $jaar = $this->getParamIfExists($config, 'jaar');
        if ($jaar == null) {
            return "<p>Geen jaartal opgegeven. Gebruik de plugin als volgt: {kampinfoaantallen jaar=\"2027\"}</p>";
        }

        $jaar = (int) $jaar;

        $db = $this->getDatabase();

        $query = $db->getQuery(true)
            ->select([
                $db->quoteName('s.naam', 'plaats'),
                'COUNT(' . $db->quoteName('c.id') . ') AS ' . $db->quoteName('aantal'),
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
            ->where([
                $db->quoteName('p.jaar') . ' = ' . $db->quote($jaar, ParameterType::STRING),
                $db->quoteName('c.akkoordHitKamp') . ' = 1',
                $db->quoteName('c.akkoordHitPlaats') . ' = 1',
                $db->quoteName('c.geannuleerd') . ' <> 1',
            ])
            ->group($db->quoteName('s.naam'))
            ->order($db->quoteName('s.naam') . ' ASC')
        ;

        $db->setQuery($query);
        $result = $db->loadObjectList();

        return $this->renderTemplate(
            static::PLUGIN_CODE,
            'landelijk',
            [
                'config' => $config,
                'result' => $result
            ]
        );
    }

}
