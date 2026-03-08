<?php
namespace HITScoutingNL\Plugin\Content\SocialMedia\Extension;

// No direct access
defined('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Event\Content\ContentPrepareEvent;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Database\ParameterType;
use Joomla\Event\SubscriberInterface;
use Joomla\Registry\Registry;


final class Socialmedia extends CMSPlugin implements SubscriberInterface
{
    use DatabaseAwareTrait;
    use HTMLRegistryAwareTrait;

    public static function getSubscribedEvents(): array {
        return [
            'onContentPrepare' => 'onContentPrepare',
        ];
    }

    /*
     * Usage:
     * 
     * {socialmedia [jaar="<jaartal>"] plaats="<Alphen|Dwingeloo|Harderwijk|Heerenveen|Nijmegen|Ommen|Zandvoort|Zeeland>" } 
     */
    public function onContentPrepare(ContentPrepareEvent $event) {
        $context = $event->getContext();
        if ($context === 'com_finder.indexer') {
            return;
        }
        $allowed_contexts = ['com_content.article', 'com_content.featured', 'com_content.category'];
        if (!in_array($context, $allowed_contexts, true)) {
            return;
        }

        $params = $event->getParams();
        if (!($params instanceof Registry)) {
            return;
        }

        $row = $event->getItem();
        if (!isset($row->id) || !(int) $row->id) {
            return;
        }

        $plugincode = 'socialmedia';
        $regex = "/{". $plugincode ."\ ([^}]+)\s*\}|{". $plugincode ."\s*\}/m";
        if (preg_match_all($regex, $row->text, $matches)) {

            $kampInfoConfig = ComponentHelper::getParams('com_kampinfo');
            $projectId = $kampInfoConfig->get('huidigeActieveJaar');

            for ($i = 0; $i < count($matches[0]); $i++) {
                $config = [];
                $config['projectId'] = $projectId;

                // collect parameters
                $pluginParameters = explode(' ', $matches[1][$i]);
                foreach ($pluginParameters as $item) {
                    if ($item !== '') {
                        list($key, $value) = explode("=", $item);
                        $config[$key] = str_replace(["'",'"'], '', $value);
                    }
                }

                // Indien geen jaar, val dan terug op huidige actieve jaar
                $jaar = $this->getParamIfExists($config, 'jaar');
                if ($jaar != null) {
                    $config['jaar'] = $jaar;
                }

                $result = $this->genereerSocialMediaOverzicht($config);

                $row->text = str_replace($matches[0][$i], $result, $row->text);
            }
        }
    }

    private function genereerSocialMediaOverzicht($config) {
        $this->loadLanguage();

        $result = $this->getData($config);

        $output = "";
        if ($result != null && ($this->isGevuld($result->facebook) || $this->isGevuld($result->instagram))) {
            $path = PluginHelper::getLayoutPath('content', 'socialmedia', 'icons');
            ob_start();
            include $path;
            $output = ob_get_clean();
        }
        return $output;
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

    private function isGevuld($value) {
        return $value != null && $value != '';
    }

    private function getParamIfExists($config, $key) {
        if (array_key_exists($key, $config)) {
            return $config[$key];
        }
        return null;
    }

}
