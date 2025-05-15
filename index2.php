<?php

use src\Application;
use src\Command\ProblemsShowCommand;
use src\Command\SolutionRunCommand;

const CURRENT_FILE = 'index2.php';

require './src/Command/AbstractCommand.php';
require './src/Command/ProblemsShowCommand.php';
require './src/Command/SolutionRunCommand.php';
require './src/Enums/InputTypeEnum.php';
require './src/InputOption.php';
require './src/Application.php';

$application = new Application();
$application->registerCommand(new ProblemsShowCommand());
$application->registerCommand(new SolutionRunCommand());
$application->run($argc, $argv);
