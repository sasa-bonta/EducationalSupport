<?php

namespace src\Services;

class SolutionService
{
    private const string PHP_START_TAG = '<?php';
    private const string PHP_END_TAG = '?>';

    public function getCodeFromFile(string $file): string
    {
        $code = file_get_contents($file);

        if ($code === false) {
            throw new \Exception("Error reading the file.");
        }

        return $code;
    }

    public function runSolution(string $code, array $problem): string
    {
        $code = self::PHP_START_TAG . PHP_EOL . $code;

        $resultToDisplay = '';
        foreach ($problem['cases'] as $idx => $case) {
            $resultToDisplay .= 'Testing case nb.' . $idx + 1 . ' with: ';
            foreach ($case['input'] as $var => $value) {
                ${$var} = $value;
                $resultToDisplay .= '$' . $var . '=' . $value . ' ';
            }

            $result = eval(self::PHP_END_TAG . $code);

            if ($result === $case['output'][0]) {
                $resultToDisplay .= ' ✅ ';
            } else {
                $resultToDisplay .= ' ❌ Expected: ' . $case['output'][0] . " Actual: $result";
            }

            $resultToDisplay .= PHP_EOL;
        }

        return $resultToDisplay;
    }
}