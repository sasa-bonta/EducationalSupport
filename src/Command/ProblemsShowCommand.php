<?php

namespace src\Command;

class ProblemsShowCommand extends AbstractCommand
{
    public string $commandName = 'show:problems';

    public function configure(): void
    {

    }

    public function handle(): void
    {
        $fileName = './storage/problems/problems.json';
        $file = fopen($fileName, "r") or die("Unable to open file!");
        $problemsJson = fread($file, filesize($fileName));
        $problems = json_decode($problemsJson, true);
        fclose($file);

        foreach ($problems as $idx => $problem) {
            print(sprintf("[%d] %s" . PHP_EOL, $idx, $problem['task']));
        }
    }
}