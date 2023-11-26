<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Controller;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Utilities\ArrayHelper;


class SitesController extends AdminController {

    public function __construct($config = [], MVCFactoryInterface $factory = null, $app = null, $input = null) {
        parent::__construct($config, $factory, $app, $input);
       $this->registerTask('nietAkkoordPlaats', 'akkoordPlaats');
    }

    public function getModel($name = 'Site', $prefix = 'Administrator', $config = array('ignore_request' => true)) {
        return parent::getModel($name, $prefix, $config);
    }

    public function akkoordPlaats() {
        $this->checkToken();

        $ids = (array) $this->input->get('cid', [], 'int');
        $values = array('akkoordPlaats' => 1, 'nietAkkoordPlaats' => 0);
        $task = $this->getTask();
        $value = ArrayHelper::getValue($values, $task, 0, 'int');

        // Remove zero values resulting from input filter
        $ids = array_filter($ids);

        if (empty($ids)) {
            $this->app->enqueueMessage('Geen plaatsen geselecteerd', 'warning');
        } else {
            $model = $this->getModel();

            // Change the state of the records.
            if (!$model->akkoordPlaats($ids, $value)) {
                $this->app->enqueueMessage($model->getError(), 'warning');
            } else {
                if ($value == 1) {
                    $ntext = '%d plaats(en) akkoord';
                } else {
                    $ntext = '%d plaats(en) niet akkoord';
                }

                $this->setMessage(Text::plural($ntext, \count($ids)));
            }
        }

        $this->setRedirect('index.php?option=com_kampinfo&view=sites');
	}
}
