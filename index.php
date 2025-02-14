<?php

$problems = [];
require './src/Enums/CommandEnum.php';
require './src/Enums/OptionEnum.php';
const CURRENT_FILE = 'index.php';

function readProblemsFile(string $fileName): void
{
    global $problems;

    if (!empty($problems)) {
        return;
    }

    $file = fopen($fileName, "r") or die("Unable to open file!");
    $problemsJson = fread($file, filesize($fileName));
    $problems = json_decode($problemsJson, true);
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

            if (!isset($commandOptions[OptionEnum::NUMBER->value])) {
                echo "‼️ Error: missing number argument." . PHP_EOL;
                exit(1);
            }

            if (!isset($commandOptions[OptionEnum::FILE->value])) {
                echo "‼️ Error: missing file argument." . PHP_EOL;
                exit(1);
            }

            checkSolution($commandOptions[OptionEnum::FILE->value], $problems[$commandOptions[OptionEnum::NUMBER->value]]);
            exit(0);
        case CommandEnum::SOLUTION_SUBMIT->value:
        default:
            exit(1);
    }
}

function checkSolution(string $file, array $problem): void
{
    $code = file_get_contents($file);

    if ($code === false) {
        die("Error reading the file.");
    }

    $code = '<?php' . PHP_EOL . $code;

    foreach ($problem['cases'] as $idx => $case) {
        echo 'Testing case nb.' . $idx + 1 . ' with: ';
        foreach ($case['input'] as $var => $value) {
            ${$var} = $value;
            echo '$' . $var . '=' . $value . ' ';
        }

        $result = eval('?>' . $code);

        if ($result === $case['output'][0]) {
            echo ' ✅ ';
        } else {
            echo ' ❌ Expected: ' . $case['output'][0] . " Actual: $result";
        }

        echo PHP_EOL;
    }
}


parseArguments($argc, $argv);
