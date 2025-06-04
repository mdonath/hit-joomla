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
use HITScoutingNL\Component\KampInfo\Administrator\Service\HTML\Icoon;
use HITScoutingNL\Component\KampInfo\Administrator\Service\HTML\Kamp;
use HITScoutingNL\Component\KampInfo\Administrator\Service\HTML\Plaats;


class KampInfoComponent extends MVCComponent implements
    BootableExtensionInterface
{
    use HTMLRegistryAwareTrait;

    public function boot(ContainerInterface $container) {
        $this->getRegistry()->register('akkoord', new Akkoord());
        $this->getRegistry()->register('kamp', new Kamp());
        $this->getRegistry()->register('plaats', new Plaats());
        $this->getRegistry()->register('icoon', new Icoon());
    }

}
