<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Extension;

use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Psr\Container\ContainerInterface;

use HITScoutingNL\Component\KampInfo\Administrator\Service\HTML\Akkoord;

defined('_JEXEC') or die;

class KampInfoComponent extends MVCComponent implements BootableExtensionInterface {
    
    use HTMLRegistryAwareTrait;

    public function boot(ContainerInterface $container) {
        $this->getRegistry()->register('akkoord', new Akkoord());
    }

}
