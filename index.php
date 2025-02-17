<?php

$problems = [];
require './src/Enums/CommandEnum.php';
require './src/Enums/OptionEnum.php';
const CURRENT_FILE = 'index.php';
const SOLUTIONS_DIRECTORY = './storage/solutions/';

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

function checkSolution(string $file, array $problem): string
{
    $code = file_get_contents($file);

    if ($code === false) {
        die("Error reading the file.");
    }

    $code = '<?php' . PHP_EOL . $code;

    $resultToDisplay = '';
    foreach ($problem['cases'] as $idx => $case) {
        $resultToDisplay .= 'Testing case nb.' . $idx + 1 . ' with: ';
        foreach ($case['input'] as $var => $value) {
            ${$var} = $value;
            $resultToDisplay .= '$' . $var . '=' . $value . ' ';
        }

        $result = eval('?>' . $code);

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
    $solution = [
        'task' => $problem['task'],
        'code' => file_get_contents($file),
        'result' => checkSolution($file, $problem),
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
        echo '########################   solution submited on [' . $fileName . '] ########################' . PHP_EOL;
        echo $solution['task'] . PHP_EOL;
        echo $solution['code'] . PHP_EOL;
        echo $solution['result'] . PHP_EOL;
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

    switch ($commandOptions[CURRENT_FILE]) {
        case CommandEnum::PROBLEMS_LIST->value:
            readProblemsFile("./storage/problems/problems.json");
            printProblems();
            exit(0);
        case CommandEnum::SOLUTION_RUN->value:
            readProblemsFile("./storage/problems/problems.json");
            global $problems;
            checkRequiredOptions($commandOptions);
            echo checkSolution($commandOptions[OptionEnum::FILE->value], $problems[$commandOptions[OptionEnum::NUMBER->value]]);
            exit(0);
        case CommandEnum::SOLUTION_SUBMIT->value:
            readProblemsFile("./storage/problems/problems.json");
            global $problems;
            checkRequiredOptions($commandOptions);
            submitSolution($commandOptions[OptionEnum::FILE->value], $problems[$commandOptions[OptionEnum::NUMBER->value]]);
            exit(0);
        case CommandEnum::SOLUTION_LIST->value:
            listSolutions();
            exit(0);
        default:
            exit(1);
    }
}

parseArguments($argc, $argv);
