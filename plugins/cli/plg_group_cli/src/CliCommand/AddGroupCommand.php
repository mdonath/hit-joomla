<?php
namespace HITScoutingNL\Plugin\Console\Group\CliCommand;

\defined('_JEXEC') or die;

use Joomla\Console\Command\AbstractCommand;
use Joomla\CMS\Factory;
use Joomla\CMS\Table\Usergroup;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Database\DatabaseInterface;
use Joomla\Filter\InputFilter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class AddGroupCommand extends AbstractCommand {

    use DatabaseAwareTrait;

    protected static $defaultName = 'group:add';

    private $cliInput;
    private $ioStyle;

    public function __construct(DatabaseInterface $db) {
        parent::__construct();

        $this->setDatabase($db);
    }

    protected function doExecute(InputInterface $input, OutputInterface $output): int {
        $this->configureIO($input, $output);

        $this->ioStyle->title('Group Add');

        // You might want to do some stuff here in Joomla
        $name         = $this->getStringFromOption('name', 'Please enter the name of the new group');
        $parentGroup  = $this->getStringFromOption('parent', 'Please enter the name of the parent group');

        $parentGroupId = (int) $this->getGroupId($parentGroup);

        if (!$parentGroupId) {
            $this->ioStyle->error("'" . $parentGroup . "' user group doesn't exist!");
            return Command::FAILURE;
        }

        $filter = new InputFilter();

        $groupTable = new Usergroup($this->getDatabase());
        $groupTable->title = $filter->clean($name, 'STRING');
        $groupTable->parent_id = $parentGroupId;
        $groupTable->check();

        if (!$groupTable->store()) {
            $this->ioStyle->error($groupTable->getError());
            return Command::FAILURE;
        }

        $this->ioStyle->success('User group '.$name . ' with parent ' . $parentGroup . ' added.');

        return 0;
    }

    private function configureIO(InputInterface $input, OutputInterface $output) {
        $this->cliInput = $input;
        $this->ioStyle  = new SymfonyStyle($input, $output);
    }

    public function getStringFromOption($option, $question): string {
        $answer = (string) $this->cliInput->getOption($option);

        while (!$answer) {
            if ($option === 'password') {
                $answer = (string) $this->iosStyle->askHidden($question);
            } else {
                $answer = (string) $this->ioStyle->ask($question);
            }
        }

        return $answer;
    }

    protected function getGroupId($groupName) {
        $db    = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select($db->quoteName('id'))
            ->from($db->quoteName('#__usergroups'))
            ->where($db->quoteName('title') . ' = :groupName')
            ->bind(':groupName', $groupName);
        $db->setQuery($query);

        return $db->loadResult();
    }

    protected function configure(): void {
        $help = <<<EOF
        The <info>%command.name%</info> command adds a new usergroup
        <info>php %command.full_name%</info>
        EOF;
        
        $this->addOption('name', null, InputOption::VALUE_OPTIONAL, 'Name of the new group');
        $this->addOption('parent', null, InputOption::VALUE_OPTIONAL, 'Name of the parent group of then new group');

        $this->setDescription('Adds a new usergroup');
        $this->setHelp($help);
    }

}