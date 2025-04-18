<?php

$problems = [];
require './src/Enums/CommandEnum.php';
require './src/Enums/OptionEnum.php';
const CURRENT_FILE = 'index.php';
const SOLUTIONS_DIRECTORY = './storage/solutions/';
const PHP_START_TAG = '<?php';
const PHP_END_TAG = '?>';

function readProblemsFile(string $fileName): void
{
    global $problems;

    if (!empty($problems)) {
        return;
    }

    $file = fopen($fileName, "r") or die("Unable to open file!");
    $problemsJson = fread($file, filesize($fileName));
    $problems = json_decode($problemsJson, true);
    fclose($file);
}

function printUsage()
{
    echo "##########################################################################################################" . PHP_EOL;
    echo "#######################################   EDUCATION SUPPORT DEMO   #######################################" . PHP_EOL;
    echo "##########################################################################################################" . PHP_EOL;
    echo "Usage: php index.php <command> <arguments[]>" . PHP_EOL;
    echo "Commands:" . PHP_EOL;
    echo "👉" . CommandEnum::PROBLEMS_LIST->value . " : show available problems" . PHP_EOL;
    echo "👉" . CommandEnum::SOLUTION_LIST->value . " : show solutions" . PHP_EOL;
    echo "👉" . CommandEnum::SOLUTION_RUN->value . " : run a solution" . PHP_EOL;
    echo "  Arguments:" . PHP_EOL;
    echo "  🚩" . OptionEnum::NUMBER->value . "    Specify number of solved problem\n";
    echo "  🚩" . OptionEnum::FILE->value . "      Name of the file with solution\n";
    echo CommandEnum::SOLUTION_SUBMIT->value . " : submit a solution" . PHP_EOL;
    echo "  Arguments:" . PHP_EOL;
    echo "  🚩" . OptionEnum::NUMBER->value . "    Specify number of solved problem\n";
    echo "  🚩" . OptionEnum::FILE->value . "      Name of the file with solution\n";
}

function printProblems()
{
    global $problems;
    foreach ($problems as $idx => $problem) {
        print(sprintf("[%d] %s" . PHP_EOL, $idx, $problem['task']));
    }
}

function getCodeFromFile(string $file): string
{
    $code = file_get_contents($file);

    if ($code === false) {
        die("Error reading the file.");
    }

    return $code;
}

function runSolution(string $code, array $problem): string
{
    $code = PHP_START_TAG . PHP_EOL . $code;

    $resultToDisplay = '';
    foreach ($problem['cases'] as $idx => $case) {
        $resultToDisplay .= 'Testing case nb.' . $idx + 1 . ' with: ';
        foreach ($case['input'] as $var => $value) {
            ${$var} = $value;
            $resultToDisplay .= '$' . $var . '=' . $value . ' ';
        }

        $result = eval(PHP_END_TAG . $code);

        if ($result === $case['output'][0]) {
            $resultToDisplay .= ' ✅ ';
        } else {
            $resultToDisplay .= ' ❌ Expected: ' . $case['output'][0] . " Actual: $result";
        }

        $resultToDisplay .= PHP_EOL;
    }

    return $resultToDisplay;
}

function submitSolution(string $file, array $problem): void
{
    $fileName = SOLUTIONS_DIRECTORY . date("Y-m-d h:i:s") . '.json';
    $submissionFile = fopen($fileName, "w");
    $code = getCodeFromFile($fileName);
    $solution = [
        'task' => $problem['task'],
        'code' => file_get_contents($file),
        'result' => runSolution($code, $problem),
    ];
    fwrite($submissionFile, json_encode($solution, JSON_PRETTY_PRINT));
    fclose($submissionFile);
}

function checkRequiredOptions($options): void
{
    if (!isset($options[OptionEnum::NUMBER->value])) {
        echo "‼️ Error: missing number argument." . PHP_EOL;
        exit(1);
    }

    if (!isset($options[OptionEnum::FILE->value])) {
        echo "‼️ Error: missing file argument." . PHP_EOL;
        exit(1);
    }
}

function listSolutions(): void
{
    $files = scandir(SOLUTIONS_DIRECTORY);
    unset($files[0], $files[1]); // remove directories . and ..
    foreach ($files as $fileName) {
        $solutionFile = fopen(SOLUTIONS_DIRECTORY . $fileName, "r") or die("Unable to open file!");
        $solution = fread($solutionFile, filesize(SOLUTIONS_DIRECTORY . $fileName));
        $solution = json_decode($solution, true);
        fclose($solutionFile);
        echo '########################   solution submitted on [' . $fileName . '] ########################' . PHP_EOL;
        echo $solution['task'] . PHP_EOL;
        echo $solution['code'] . PHP_EOL;
        echo $solution['result'] . PHP_EOL;
    }
}

function checkSolution(string $fileName, array $problem): void
{
    $solutionFile = fopen(SOLUTIONS_DIRECTORY . $fileName, "r") or die("Unable to open file!");
    $solution = fread($solutionFile, filesize(SOLUTIONS_DIRECTORY . $fileName));
    $solution = json_decode($solution, true);
    fclose($solutionFile);
    echo '########################   checking submitted solution : [' . $fileName . '] ########################' . PHP_EOL;

    if (strcmp($solution['task'], $problem['task']) !== 0) {
        echo '‼️ tasks differ' . PHP_EOL;
        echo "  👉 solution's task: " . $solution['task'] . PHP_EOL;
        echo "  👉 task's task:     " . $problem['task'] . PHP_EOL;
    } else {
        echo $solution['task'] . PHP_EOL . PHP_EOL;
        echo runSolution($solution['code'], $problem);
    }
}

function parseArguments(int $argc, array $argv): void
{
    $commandOptions = [];
    for ($i = 0; $i < $argc; $i += 2) {
        if (isset($argv[$i + 1])) {
            $commandOptions[$argv[$i]] = $argv[$i + 1];
        }
    }

    if (empty($commandOptions[CURRENT_FILE])) {
        printUsage();
        exit(0);
    }

    readProblemsFile("./storage/problems/problems.json");
    global $problems;

    switch ($commandOptions[CURRENT_FILE]) {
        case CommandEnum::PROBLEMS_LIST->value:
            printProblems();
            exit(0);
        case CommandEnum::SOLUTION_RUN->value:
            checkRequiredOptions($commandOptions);
            $code = getCodeFromFile($commandOptions[OptionEnum::FILE->value]);
            echo runSolution($code, $problems[$commandOptions[OptionEnum::NUMBER->value]]);
            exit(0);
        case CommandEnum::SOLUTION_SUBMIT->value:
            checkRequiredOptions($commandOptions);
            submitSolution($commandOptions[OptionEnum::FILE->value], $problems[$commandOptions[OptionEnum::NUMBER->value]]);
            exit(0);
        case CommandEnum::SOLUTION_LIST->value:
            listSolutions();
            exit(0);
        case CommandEnum::SOLUTION_CHECK->value:
            checkRequiredOptions($commandOptions);
            checkSolution($commandOptions[OptionEnum::FILE->value], $problems[$commandOptions[OptionEnum::NUMBER->value]]);
            exit(0);
        default:
            exit(1);
    }
}

parseArguments($argc, $argv);
