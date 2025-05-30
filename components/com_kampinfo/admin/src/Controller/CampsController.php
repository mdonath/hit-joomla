<?php

namespace HITScoutingNL\Component\KampInfo\Administrator\Controller;

\defined('_JEXEC') or die('Restricted Access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Router\Route;
use Joomla\Utilities\ArrayHelper;


class CampsController extends AdminController {

    public function __construct($config = [], MVCFactoryInterface $factory = null, $app = null, $input = null) {
        parent::__construct($config, $factory, $app, $input);
        $this->registerTask('nietAkkoordPlaats', 'akkoordPlaats');
        $this->registerTask('nietAkkoordKamp', 'akkoordKamp');
    }

    public function getModel($name = 'Camp', $prefix = 'Administrator', $config = array('ignore_request' => true)) {
        return parent::getModel($name, $prefix, $config);
    }

    public function akkoordPlaats() {
        // Check for request forgeries
        $this->checkToken();

        $cid   = (array) $this->input->get('cid', [], 'int');
        $data  = ['akkoordPlaats' => 1, 'nietAkkoordPlaats' => 0];
        $task  = $this->getTask();
        $value = ArrayHelper::getValue($data, $task, 0, 'int');

        // Remove zero values resulting from input filter
        $cid = array_filter($cid);

        if (empty($cid)) {
            $this->getLogger()->warning(Text::_('Geen plaatsen geselecteerd'), ['category' => 'jerror']);
        } else {
            // Get the model.
            $model = $this->getModel();

            // Change the state of the records.
            try {
                $model->akkoordPlaats($cid, $value);
                $errors = $model->getErrors();
                $ntext  = null;

                if ($errors) {
                    $this->app->enqueueMessage($model->getError(), 'warning');
                } else {
                    if ($value == 1) {
                        $ntext = '%d plaats(en) akkoord';
                    } else {
                        $ntext = '%d plaats(en) niet akkoord';
                    }

                    $this->setMessage(Text::plural($ntext, \count($cid)));
                }
            } catch (\Exception $e) {
                $this->setMessage($e->getMessage(), 'error');
            }
        }

        $this->setRedirect(Route::_('index.php?option=' . $this->option . '&view=camps', false));
    }

    public function akkoordKamp() {
        // Check for request forgeries
        $this->checkToken();

        $cid = (array) $this->input->get('cid', [], 'int');
        $data = array('akkoordKamp' => 1, 'nietAkkoordKamp' => 0);
        $task = $this->getTask();
        $value = ArrayHelper::getValue($data, $task, 0, 'int');

        // Remove zero values resulting from input filter
        $cid = array_filter($cid);

        if (empty($cid)) {
            $this->getLogger()->warning(Text::_('Geen kampen geselecteerd'), ['category' => 'jerror']);
        } else {
            // Get the model.
            $model = $this->getModel();

            // Change the state of the records.
            try {
                $model->akkoordKamp($cid, $value);
                $errors = $model->getErrors();
                $ntext  = null;

                if ($errors) {
                    $this->app->enqueueMessage($model->getError(), 'warning');
                } else {
                    if ($value == 1) {
                        $ntext = '%d kamp(en) akkoord';
                    } else {
                        $ntext = '%d kamp(en) niet akkoord';
                    }

                    $this->setMessage(Text::plural($ntext, \count($cid)));
                }
            } catch (\Exception $e) {
                $this->setMessage($e->getMessage(), 'error');
            }
        }

        $this->setRedirect(Route::_('index.php?option=' . $this->option . '&view=camps', false));
    }

}
