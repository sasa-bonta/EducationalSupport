<?php

use src\Application;
use src\ShowProblemsCommand;

const CURRENT_FILE = 'index2.php';

require './src/AbstractCommand.php';
require './src/ShowProblemsCommand.php';
require './src/Enums/InputTypeEnum.php';
require './src/InputOption.php';
require './src/Application.php';

$application = new Application();
$application->registerCommand(new ShowProblemsCommand());
$application->run($argc, $argv);
