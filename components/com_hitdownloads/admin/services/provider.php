<?php

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

use HITScoutingNL\Component\HitDownloads\Administrator\Extension\HitDownloadsComponent;

return new class () implements ServiceProviderInterface {
    
    public function register(Container $container): void {

        $container->registerServiceProvider(new MVCFactory('\\HITScoutingNL\\Component\\HitDownloads'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\HITScoutingNL\\Component\\HitDownloads'));

        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                $component = new HitDownloadsComponent($container->get(ComponentDispatcherFactoryInterface::class));

                $component->setMVCFactory($container->get(MVCFactoryInterface::class));

                return $component;
            }
        );
    }
};
