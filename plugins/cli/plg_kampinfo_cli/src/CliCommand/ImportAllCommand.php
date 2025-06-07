<?php
namespace HITScoutingNL\Plugin\Console\KampInfo\CliCommand;

\defined('_JEXEC') or die;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use HITScoutingNL\Library\KampInfo\ImportExport\KampInfoImporter;


class ImportAllCommand extends AbstractKampInfoCommand {

    public const IMPORT_SUCCESSFUL = 0;
    public const IMPORT_FAILED = 1;

    protected static $defaultName = 'kampinfo:import:all';

    protected function doExecute(InputInterface $input, OutputInterface $output): int {
        $this->configureIO($input, $output);

        $this->ioStyle->title('Import All');

        $fileName = $this->cliInput->getArgument('file');
        
        try {
            $importer = new KampInfoImporter();
            $importer->importAlles($fileName);
            $this->ioStyle->success('Imported '. $fileName . '.');
            return self::IMPORT_SUCCESSFUL;
        } catch (GenericDataException $e) {
            $this->ioStyle->error($e);
            return self::IMPORT_FAILED;
        }
    }

    protected function configure(): void {
        $help = <<<EOF
        The <info>%command.name%</info> imports all data into KampInfo
        <info>php %command.full_name%</info>
        EOF;
        
        $this->addArgument('file', InputArgument::REQUIRED, 'Name of data file');

        $this->setDescription('Imports data into KampInfo');
        $this->setHelp($help);
    }

}
