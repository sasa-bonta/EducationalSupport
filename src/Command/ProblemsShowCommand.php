<?php

namespace src\Command;

use src\Services\ProblemService;

class ProblemsShowCommand extends AbstractCommand
{
    public string $commandName = 'problems:list';

    public function configure(): void
    {

    }

    public function handle(): void
    {
        $problemService = new ProblemService();

        $problems = $problemService->readProblemsFile(PROBLEMS_FILE);

        foreach ($problems as $idx => $problem) {
            print(sprintf("[%d] %s" . PHP_EOL, $idx, $problem['task']));
        }
    }
}