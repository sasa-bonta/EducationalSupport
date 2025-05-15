<?php

use src\Application;
use src\Command\ProblemsShowCommand;
use src\Command\SolutionRunCommand;

const CURRENT_FILE = 'index2.php';
const PROBLEMS_FILE = './storage/problems/problems.json';

require './src/Application.php';
require './src/Command/AbstractCommand.php';
require './src/Command/ProblemsShowCommand.php';
require './src/Command/SolutionRunCommand.php';
require './src/Enums/InputTypeEnum.php';
require './src/InputOption.php';
require './src/Services/ProblemService.php';
require './src/Services/SolutionService.php';

$application = new Application();
$application->registerCommand(new ProblemsShowCommand());
$application->registerCommand(new SolutionRunCommand());
$application->run($argc, $argv);
