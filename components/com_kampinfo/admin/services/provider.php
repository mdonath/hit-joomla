<?php

defined('_JEXEC') or die;

use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\HTML\Registry;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use HITScoutingNL\Component\KampInfo\Administrator\Extension\KampInfoComponent;

return new class implements ServiceProviderInterface {
    
    public function register(Container $container): void {

        $container->registerServiceProvider(new ComponentDispatcherFactory('\\HITScoutingNL\\Component\\KampInfo'));
        $container->registerServiceProvider(new MVCFactory('\\HITScoutingNL\\Component\\KampInfo'));

        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                $component = new KampInfoComponent($container->get(ComponentDispatcherFactoryInterface::class));

                $component->setMVCFactory($container->get(MVCFactoryInterface::class));
                $component->setRegistry($container->get(Registry::class));

                return $component;
            }
        );
    }
};
