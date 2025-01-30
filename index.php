<?php

$problems = [
    (object)[
        'task' => 'Напишите программу, которая вычисляет следующее выражение: x*y+y*z. Текущие значения вещественных переменных x, y и z считываются с клавиатуры.',
        'cases' => [
            (object)[
                'input' => ['x' => 1, 'y' => 2, 'z' => 3],
                'output' => [8],
            ],
            (object)[
                'input' => ['x' => -23265345, 'y' => 32875768, 'z' => -48229756],
                'output' => [-2350456353612568],
            ],
            (object)[
                'input' => ['x' => 176837.632274, 'y' => -28835.8347382, 'z' => -87893.97492],
                'output' => [-2564764604.471031],
            ]
        ]
    ],
    (object)[
        'task' => 'Напишите программу, которая вычисляет следующее выражение: (x*y+y)*z. Текущие значения вещественных переменных x, y и z считываются с клавиатуры.',
        'cases' => [
            (object)[
                'input' => ['x' => 1, 'y' => 2, 'z' => 3],
                'output' => [12],
            ],
            (object)[
                'input' => ['x' => -232345, 'y' => 328768, 'z' => -482756],
                'output' => [36876413974321152],
            ],
            (object)[
                'input' => ['x' => 1737.6374, 'y' => -2835.8382, 'z' => -8793.9742],
                'output' => [43358640149.96082],
            ]
        ]
    ]
];

enum OptionEnum: string
{
    case HELP = '-h';
    case HELP_LONG = '--help';
    case PROBLEMS = '-p';
    case PROBLEMS_LONG = '--problems';
    case NUMBER = '-n';
    case NUMBER_LONG = '--number';
    case FILE = '-f';
    case FILE_LONG = '--file';
}

function printUsage()
{
    echo "Usage: php index.php [options]\n";
    echo "Options:\n";
    echo "  " . OptionEnum::HELP->value . ", " . OptionEnum::HELP_LONG->value . "      Show this help message\n";
    echo "  " . OptionEnum::PROBLEMS->value . ", " . OptionEnum::PROBLEMS_LONG->value . "  Show available problems\n";
    echo "  " . OptionEnum::NUMBER->value . ", " . OptionEnum::NUMBER_LONG->value . "    Specify number of solved problem\n";
    echo "  " . OptionEnum::FILE->value . ", " . OptionEnum::FILE_LONG->value . "      Name of the file with solution\n";
}

function printProblems()
{
    global $problems;
    foreach ($problems as $idx => $problem) {
        echo sprintf("[%d] %s" . PHP_EOL, $idx, $problem->task);
    }
}

function parseArguments(int $argc, array $argv): array
{
    if ($argc < 2) {
        printUsage();
    }

    for ($i = 1; $i < $argc; $i++) {
        switch ($argv[$i]) {
            case OptionEnum::HELP->value:
            case OptionEnum::HELP_LONG->value:
                printUsage();
                exit(0);
            case OptionEnum::PROBLEMS->value:
            case OptionEnum::PROBLEMS_LONG->value:
                echo "Available problems:" . PHP_EOL;
                printProblems();
                exit(0);
            case OptionEnum::NUMBER->value:
            case OptionEnum::NUMBER_LONG->value:
                if (isset($argv[$i + 1])) {
                    $number = $argv[++$i];
                } else {
                    echo "Error: Option '-n' requires an argument." . PHP_EOL;
                    exit(1);
                }
                break;
            case OptionEnum::FILE->value:
            case OptionEnum::FILE_LONG->value:
                if (isset($argv[$i + 1])) {
                    $file = $argv[++$i];
                } else {
                    echo "Error: Option '-f' requires an argument." . PHP_EOL;
                    exit(1);
                }
                break;
            default:
                echo "Error: Unknown option '{$argv[$i]}'." . PHP_EOL;
                printUsage();
                exit(1);
        }
    }

    if (!isset($number)) {
        echo "Error: missing number argument." . PHP_EOL;
        printUsage();
        exit(1);
    }

    if (!isset($file)) {
        echo "Error: missing file argument." . PHP_EOL;
        printUsage();
        exit(1);
    }

    return [$number, $file];
}

function checkSolution(string $file, object $problem): void
{
    $code = file_get_contents($file);

    if ($code === false) {
        die("Error reading the file.");
    }

    $code = '<?php' . PHP_EOL . $code;

    foreach ($problem->cases as $idx => $case) {
        echo 'Testing case nb.' . $idx + 1 . ' with: ';
        foreach ($case->input as $var => $value) {
            ${$var} = $value;
            echo '$' . $var . '=' . $value . ' ';
        }

        $result = eval('?>' . $code);

        if ($result === $case->output[0]) {
            echo ' ✅ ';
        } else {
            echo ' ❌ Expected: ' . $case->output[0] . " Actual: $result";
        }

        echo PHP_EOL;
    }
}

[$number, $file] = parseArguments($argc, $argv);
checkSolution($file, $problems[$number]);
