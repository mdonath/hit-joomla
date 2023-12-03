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


abstract class AbstractKampInfoCommand extends AbstractCommand {

    protected $cliInput;
    protected $ioStyle;

    public function __construct() {
        parent::__construct();
    }

    protected function configureIO(InputInterface $input, OutputInterface $output) {
        $this->cliInput = $input;
        $this->ioStyle  = new SymfonyStyle($input, $output);
    }

    protected function getStringFromOption($option, $question): string {
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

}
