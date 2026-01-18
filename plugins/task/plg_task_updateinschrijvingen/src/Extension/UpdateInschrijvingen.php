<?php

namespace HITScoutingNL\Plugin\Task\UpdateInschrijvingen\Extension;

\defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Component\Scheduler\Administrator\Event\ExecuteTaskEvent;
use Joomla\Component\Scheduler\Administrator\Task\Status as TaskStatus;
use Joomla\Component\Scheduler\Administrator\Traits\TaskPluginTrait;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Database\ParameterType;
use Joomla\Event\DispatcherInterface;
use Joomla\Event\SubscriberInterface;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Path;
use Joomla\Http\HttpFactory;


final class UpdateInschrijvingen extends CMSPlugin implements SubscriberInterface {

    use DatabaseAwareTrait;
    use TaskPluginTrait;

    private const TASKS_MAP = [
        'update.inschrijvingen' => [
            'langConstPrefix' => 'PLG_TASK_UPDATEINSCHRIJVINGEN',
            'method'          => 'updateInschrijvingen',
            'form'            => 'updateInschrijvingen',
        ],
    ];

    public static function getSubscribedEvents(): array {
        return [
            'onTaskOptionsList'    => 'advertiseRoutines',
            'onExecuteTask'        => 'standardRoutineHandler',
            'onContentPrepareForm' => 'enhanceTaskItemForm',
        ];
    }

    protected $autoloadLanguage = true;

    private $httpFactory;
    private $rootDirectory;

    public function __construct(DispatcherInterface $dispatcher, array $config, HttpFactory $httpFactory, string $rootDirectory) {
        parent::__construct($dispatcher, $config);

        $this->httpFactory   = $httpFactory;
        $this->rootDirectory = $rootDirectory;
    }

    private function updateInschrijvingen(ExecuteTaskEvent $event): int {
        $this->logTask('HIT Inschrijvingen worden bijgewerkt', 'info');

        $id     = $event->getTaskId();
        $params = $event->getArgument('params');

        $url     = $params->url ?? '';
        $timeout = $params->timeout;

        if (empty($url)) {
            $this->logTask('Geen download URL opgegeven', 'error');
            return TaskStatus::FAILED;
        }

        try {
            $response = $this->httpFactory->getHttp([])->get($url, [], $timeout);
        } catch (\Exception $e) {
            $this->logTask($this->getApplication()->getLanguage()->_('PLG_TASK_REQUESTS_TASK_GET_REQUEST_LOG_TIMEOUT'));

            return TaskStatus::TIMEOUT;
        }

        $responseCode = $response->code;
        $responseBody = $response->body;

        $responseFilename = Path::clean($this->rootDirectory . "/task_updateinschrijvingen_{$id}_response.json");

        try {
            File::write($responseFilename, $responseBody);
            $this->snapshot['output_file'] = $responseFilename;
            $responseStatus                = 'SAVED';
        } catch (\Exception) {
            $this->logTask($this->getApplication()->getLanguage()->_('PLG_TASK_REQUESTS_TASK_GET_REQUEST_LOG_UNWRITEABLE_OUTPUT'), 'error');
            $responseStatus = 'NOT_SAVED';
        }

        $data = json_decode($responseBody, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->logTask('Ongeldige JSON response ontvangen', 'error');
            return TaskStatus::FAILED;
        }

        $jaar = (int) ($data['year'] ?? 0);
        $events = $data['events'] ?? [];
        $totalAffectedRows = 0;

        $db     = $this->getDatabase();
        foreach ($events as $event) {

            // deelnemers
            $hitcamp_id                 = (int) $event['kampinfo_id'] ?? 0;
            $minimumAantalDeelnemers    = (int) $event['deelnemers']['minimum'] ?? 0;
            $gereserveerd               = (int) $event['deelnemers']['reserveringen'] ?? 0;
            $aantalDeelnemers           = (int) $event['deelnemers']['ingeschreven'] ?? 0;
            $maximumAantalDeelnemers    = (int) $event['deelnemers']['maximum'] ?? 0;
            // subgroepjes
            $aantalSubgroepen           = (int) $event['subgroepjes']['ingeschreven'] ?? 0;
            $maximumAantalSubgroepjes   = (int) $event['subgroepjes']['maximum'] ?? 0;

            $query  = $db->getQuery(true)
                -> clear()
                -> update($db->quoteName('#__kampinfo_hitcamp'))
                // deelnemers
                -> set($db->quoteName('minimumAantalDeelnemers') . ' = :minimumAantalDeelnemers')
                -> set($db->quoteName('gereserveerd') . ' = :gereserveerd')
                -> set($db->quoteName('aantalDeelnemers') . ' = :aantalDeelnemers')
                -> set($db->quoteName('maximumAantalDeelnemers') . ' = :maximumAantalDeelnemers')
                -> bind(':minimumAantalDeelnemers', $minimumAantalDeelnemers, ParameterType::INTEGER)
                -> bind(':gereserveerd', $gereserveerd, ParameterType::INTEGER)
                -> bind(':aantalDeelnemers', $aantalDeelnemers, ParameterType::INTEGER)
                -> bind(':maximumAantalDeelnemers', $maximumAantalDeelnemers, ParameterType::INTEGER)
                // subgroepjes
                -> set($db->quoteName('aantalSubgroepen') . ' = :aantalSubgroepen')
                -> set($db->quoteName('maximumAantalSubgroepjes') . ' = :maximumAantalSubgroepjes')
                -> bind(':aantalSubgroepen', $aantalSubgroepen, ParameterType::INTEGER)
                -> bind(':maximumAantalSubgroepjes', $maximumAantalSubgroepjes, ParameterType::INTEGER)
                -> where($db->quoteName('id') . ' = :hitcamp_id')
                -> bind(':hitcamp_id', $hitcamp_id, ParameterType::INTEGER)
            ;

            try {
                $db->setQuery($query);
                $db->execute();
                $affectedRows = $db->getAffectedRows();
                $totalAffectedRows += $affectedRows;
                $this->logTask("Hitcamp ID {$hitcamp_id} bijgewerkt, Affected Rows: {$affectedRows}", 'info');
            } catch (\RuntimeException) {
                return TaskStatus::KNOCKOUT;
            }
        }

        $this->snapshot['output']      = <<< EOF
======= Task Output Body =======
> URL: $url
> Response Code: $responseCode
> Response: $responseStatus
> Output File: $responseFilename
> Affected rows: $totalAffectedRows
================================
EOF;

        if ($response->code !== 200 && $response->code !== 304) {
            return TaskStatus::KNOCKOUT;
        }

        $soort = 'INSC';
        $melding = "Er zijn nu $totalAffectedRows kampen gewijzigd met hun inschrijvingen t.o.v. de vorige keer";
        try {
            $query = $db->getQuery(true)
                -> insert('#__kampinfo_downloads')
                -> set($db->quoteName('jaar') . ' = :jaar')
                -> bind(':jaar', $jaar, ParameterType::INTEGER)
                -> set($db->quoteName('soort') . ' = :soort')
                -> bind(':soort', $soort, ParameterType::STRING)
                -> set($db->quoteName('melding') . ' = :melding')
                -> bind(':melding', $melding, ParameterType::STRING)
            ;
            $db->setQuery($query);
            $db->execute();
        } catch (\RuntimeException) {
            return TaskStatus::KNOCKOUT;
        }

        return TaskStatus::OK;
    }

}
