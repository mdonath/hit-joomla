<?php
namespace HITScoutingNL\Plugin\Console\KampInfo\Extension;

\defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Event\SubscriberInterface;
use Joomla\Application\ApplicationEvents;
use Joomla\CMS\Factory;
use HITScoutingNL\Plugin\Console\KampInfo\CliCommand\ImportAllCommand;
use HITScoutingNL\Plugin\Console\KampInfo\CliCommand\ExportAllCommand;


class KampinfoConsolePlugin extends CMSPlugin implements SubscriberInterface {

    use DatabaseAwareTrait;

    public static function getSubscribedEvents(): array {
        return [
            \Joomla\Application\ApplicationEvents::BEFORE_EXECUTE => 'registerCommands',
        ];
    }

    public function registerCommands(): void {
        $app = Factory::getApplication();
        $app->addCommand(new ImportAllCommand());
        $app->addCommand(new ExportAllCommand($this->getDatabase()));
    }

}
