<?php

namespace HITScoutingNL\Library\KampInfo\ContentPlugin;

\defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Event\Content\ContentPrepareEvent;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Event\SubscriberInterface;
use Joomla\Registry\Registry;


abstract class AbstractContentPlugin extends CMSPlugin implements SubscriberInterface {

    use DatabaseAwareTrait;
    use HTMLRegistryAwareTrait;

    private $kampInfoConfig;

    public static function getSubscribedEvents(): array {
        return [
            'onContentPrepare' => 'onContentPrepare',
        ];
    }

    protected abstract function getPluginName();
    protected abstract function getAllowedContexts();
    protected abstract function renderPlugin($pluginParameters);

    public function onContentPrepare(ContentPrepareEvent $event) {
        $context = $event->getContext();
        if ($context === 'com_finder.indexer') {
            return;
        }
        if (!in_array($context, $this->getAllowedContexts(), true)) {
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

        $plugincode = $this->getPluginName();
        $regex = "/{". $plugincode ."\ ([^}]+)\s*\}|{". $plugincode ."\s*\}/m";

        if (preg_match_all($regex, $row->text, $matches)) {

            for ($i = 0; $i < count($matches[0]); $i++) {
                $pluginParameters = $this->collectPluginParameters($matches, $i);

                $html = $this->renderPlugin($pluginParameters);

                $row->text = str_replace($matches[0][$i], $html, $row->text);
            }
        }
    }

    protected function getParamIfExists($config, $key) {
        if (array_key_exists($key, $config)) {
            return $config[$key];
        }
        return null;
    }

    protected function collectPluginParameters($matches, $i) {
        $config = [];
        $pluginParameters = explode(' ', $matches[1][$i]);
        foreach ($pluginParameters as $item) {
            if ($item !== '') {
                list($key, $value) = explode("=", $item);
                $config[$key] = str_replace(["'", '"'], '', $value);
            }
        }
        return $config;
    }

    protected function renderTemplate($plugin, $template, $variables) {
        foreach ($variables as $key => $value) {
            $$key = $value;
        }

        $path = PluginHelper::getLayoutPath('content', $plugin, $template);
        ob_start();
        include $path;
        $output = ob_get_clean();
        return $output;
    }

    protected function isGevuld($value) {
        return $value != null && $value != '';
    }

    protected function getKampInfoConfig() {
        if ($this->kampInfoConfig == null) {
            $this->kampInfoConfig = ComponentHelper::getParams('com_kampinfo');
        }
        return $this->kampInfoConfig;
    }

}
