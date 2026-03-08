<?php

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\HTML\Registry;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Database\DatabaseInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;

use HITScoutingNL\Plugin\Content\KampInfo\Extension\Kampinfo;

return new class () implements ServiceProviderInterface {

    public function register(Container $container) {
        $container->set(
            PluginInterface::class,
            function (Container $container) {
                $plugin = new Kampinfo(
                    $container->get(DispatcherInterface::class),
                    (array) PluginHelper::getPlugin('content', 'kampinfo')
                );
                $plugin->setRegistry($container->get(Registry::class));
                $plugin->setApplication(Factory::getApplication());
                $plugin->setDatabase($container->get(DatabaseInterface::class));

                return $plugin;
            }
        );
    }

};
