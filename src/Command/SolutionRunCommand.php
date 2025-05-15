<?php

namespace src\Command;

use src\Enums\InputTypeEnum;
use src\Services\ProblemService;
use src\Services\SolutionService;

class SolutionRunCommand extends AbstractCommand
{
    public string $commandName = 'solution:run';

    public function configure(): void
    {
        $this
            ->addOption(
                'problemNumber',
                'n',
                InputTypeEnum::INT,
                'Problem number',
                true
            )
            ->addOption(
                'fileName',
                'f',
                InputTypeEnum::STRING,
                'Name of the file with solution',
                true
            );
    }

    public function handle(): void
    {
        $problemService = new ProblemService();
        $solutionService = new SolutionService();

        $problems = $problemService->readProblemsFile(PROBLEMS_FILE);
        $file = $this->getOption('fileName');
        $code = $solutionService->getCodeFromFile($file);
        $problemNumber = $this->getOption('problemNumber');

        echo $solutionService->runSolution($code, $problems[$problemNumber]);
    }
}