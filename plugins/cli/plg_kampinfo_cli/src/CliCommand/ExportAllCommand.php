<?php
namespace HITScoutingNL\Plugin\Console\KampInfo\CliCommand;

\defined('_JEXEC') or die;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use HITScoutingNL\Library\KampInfo\ImportExport\KampInfoExporter;


class ExportAllCommand extends AbstractKampInfoCommand {

    public const EXPORT_SUCCESSFUL = 0;
    public const EXPORT_FAILED = 1;

    protected static $defaultName = 'kampinfo:export:all';

    protected function doExecute(InputInterface $input, OutputInterface $output): int {
        $this->configureIO($input, $output);

        $this->ioStyle->title('Export All');

        $year = $this->cliInput->getOption('year');
        $fileName = $this->cliInput->getArgument('file');
        
        try {
            $exporter = new KampInfoExporter();
            $items = $exporter->exportAlles($year);
            $hit = new \stdClass();
            $hit->projecten = $items;

            file_put_contents($fileName, json_encode($hit));

            $this->ioStyle->success('Exported to '. $fileName . '.');

            return self::EXPORT_SUCCESSFUL;
        } catch (GenericDataException $e) {
            $this->ioStyle->error($e);
            return self::EXPORT_FAILED;
        }
    }

    protected function configure(): void {
        $help = <<<EOF
        The <info>%command.name%</info> exports all data from KampInfo to a file
        \n<info>php %command.full_name% <output-file></info>
        EOF;
        
        $this->addArgument('file', InputArgument::REQUIRED, 'Name of data file');
        $this->addOption('year', null, InputOption::VALUE_OPTIONAL, 'Year to export (exports all if not specified)');

        $this->setDescription('Exports data from KampInfo');
        $this->setHelp($help);
    }

}
