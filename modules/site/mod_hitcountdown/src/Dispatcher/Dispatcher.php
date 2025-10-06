<?php
namespace HITScoutingNL\Module\HitCountdown\Site\Dispatcher;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Dispatcher\DispatcherInterface;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;
use Joomla\CMS\Input\Input;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;

use HITScoutingNL\Module\HitCountdown\Site\Helper\HitCountdownHelper;


class Dispatcher extends AbstractModuleDispatcher implements DispatcherInterface, HelperFactoryAwareInterface {

    use HelperFactoryAwareTrait;

    protected function getLayoutData(): array {
        $data = parent::getLayoutData();

        $helper = $this->getHelperFactory()->getHelper('HitCountdownHelper');
        $username = $helper->getLoggedonUsername('Guest');

        $data['textDays'] = Text::_('MOD_HITCOUNTDOWN_LABEL_DAYS');
        $data['textHours'] = Text::_('MOD_HITCOUNTDOWN_LABEL_HOURS');
        $data['textMinutes'] = Text::_('MOD_HITCOUNTDOWN_LABEL_MINUTES');
        $data['textSeconds'] = Text::_('MOD_HITCOUNTDOWN_LABEL_SECONDS');

        return $data;
    }

}