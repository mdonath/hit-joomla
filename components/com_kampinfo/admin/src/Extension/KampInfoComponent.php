<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Extension;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Component\Router\RouterServiceInterface;
use Joomla\CMS\Component\Router\RouterServiceTrait;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Psr\Container\ContainerInterface;

use HITScoutingNL\Component\KampInfo\Administrator\Service\HTML\Akkoord;


class KampInfoComponent extends MVCComponent implements
    BootableExtensionInterface,
    RouterServiceInterface
{
    use HTMLRegistryAwareTrait;
    use RouterServiceTrait;

    public function boot(ContainerInterface $container) {
        $this->getRegistry()->register('akkoord', new Akkoord());
    }

}
