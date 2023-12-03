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


class ImportAllCommand extends AbstractKampInfoCommand {

    protected static $defaultName = 'kampinfo:import:all';

    protected function doExecute(InputInterface $input, OutputInterface $output): int {
        $this->configureIO($input, $output);

        $this->ioStyle->title('Import All');

        $fileName = $this->getStringFromOption('file', 'Please enter the name of the file to import');
        
        $filter = new InputFilter();
        // TODO: sanitize input

        try {
            $importer = new KampInfoImporterExporter();
            $importer->importAlles($fileName);
            $this->ioStyle->success('Imported '. $fileName . '.');
            return 0;
        } catch (GenericDataException $e) {
            $this->ioStyle->error($e);
            return 1;
        }
    }

    protected function configure(): void {
        $help = <<<EOF
        The <info>%command.name%</info> imports all data into KampInfo
        <info>php %command.full_name%</info>
        EOF;
        
        $this->addOption('file', null, InputOption::VALUE_REQUIRED, 'Name of data file');

        $this->setDescription('Imports data into KampInfo');
        $this->setHelp($help);
    }

}
