<?php
namespace HITScoutingNL\Plugin\Console\KampInfo\CliCommand;

\defined('_JEXEC') or die;

use Joomla\Console\Command\AbstractCommand;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Database\DatabaseInterface;
use Joomla\Filter\InputFilter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use HITScoutingNL\Component\KampInfoImExport\Administrator\Common\KampInfoImporterExporter;


class ExportAllCommand extends AbstractKampInfoCommand {

    use DatabaseAwareTrait;

    protected static $defaultName = 'kampinfo:export:all';

    public function __construct(DatabaseInterface $db) {
        parent::__construct();

        $this->setDatabase($db);
    }

    protected function doExecute(InputInterface $input, OutputInterface $output): int {
        $this->configureIO($input, $output);

        $this->ioStyle->title('Export All');

        $fileName = $this->getStringFromOption('file', 'Please enter the name of the file to export to');
        $year = $this->getStringFromOption('year', 'Please enter the year to export (leave empty to export all years)');
        
        $filter = new InputFilter();
        // TODO: sanitize input

        $exporter = new KampInfoImporterExporter();
        $items = $exporter->exportAll($year);

        file_put_contents($fileName, json_encode($items));

        return 0;
    }

    protected function configure(): void {
        $help = <<<EOF
        The <info>%command.name%</info> exports all data from KampInfo to a file
        <info>php %command.full_name%</info>
        EOF;
        
        $this->addOption('file', null, InputOption::VALUE_REQUIRED, 'Name of data file');
        $this->addOption('year', null, InputOption::VALUE_OPTIONAL, 'Year to export (exports all if not specified)');

        $this->setDescription('Exports data from KampInfo');
        $this->setHelp($help);
    }

}
