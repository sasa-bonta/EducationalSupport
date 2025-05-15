<?php

namespace src\Services;

class ProblemService
{
    /**
     * @throws \Exception
     */
    function readProblemsFile(string $fileName): array
    {
        $file = fopen($fileName, "r") or throw new \Exception("Unable to open file!");
        $problemsJson = fread($file, filesize($fileName));
        $problems = json_decode($problemsJson, true);
        fclose($file);

        return $problems;
    }
}