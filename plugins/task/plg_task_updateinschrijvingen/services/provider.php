<?php

\defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Database\DatabaseInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Http\HttpFactory;

use HITScoutingNL\Plugin\Task\UpdateInschrijvingen\Extension\UpdateInschrijvingen;

return new class () implements ServiceProviderInterface {

    public function register(Container $container): void {
        $container->set(
            PluginInterface::class,
            function (Container $container): UpdateInschrijvingen {
                $plugin = new UpdateInschrijvingen(
                    $container->get(DispatcherInterface::class),
                    (array) PluginHelper::getPlugin('task', 'updateinschrijvingen'),
                    new HttpFactory(),
                    JPATH_ROOT . '/tmp'
                );
                $plugin->setApplication(Factory::getApplication());
                $plugin->setDatabase($container->get(DatabaseInterface::class));

                return $plugin;
            }
        );
    }
};
