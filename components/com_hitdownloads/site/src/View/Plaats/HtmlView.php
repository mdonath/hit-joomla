<?php

namespace HITScoutingNL\Component\HitDownloads\Site\View\Plaats;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;


class HtmlView extends BaseHtmlView {

    protected $jaar;
    protected $plaats;
    protected $files;

    public function display($tpl = null) {
        $model      = $this->getModel();
        $app        = Factory::getApplication();
        $document   = $app->getDocument();

        $this->jaar = $model->getJaar();
        $this->plaats = $model->getPlaats();
        $this->files = $model->getFiles($this->jaar, $this->plaats);

        return parent::display($tpl);
    }

}
