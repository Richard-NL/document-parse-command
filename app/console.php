<?php
use Silex\Application as SilexApplication;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\InputOption;
use Rsh\TermsAndConditions\Command\WordToTextCommand;
use Rsh\TermsAndConditions\Command\TextToHtmlCommand;

chdir(dirname(__DIR__));
$loader = require_once 'vendor/autoload.php';
set_time_limit(0);
$app = new SilexApplication();

require_once('app/bootstrap.php');
$console = new ConsoleApplication('Silex - Command', '1.0');
$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
$console->add(new WordToTextCommand());
$console->add(new TextToHtmlCommand());
$console->run();