<?php

namespace src;

use src\Enums\InputTypeEnum;

class ShowProblemsCommand extends AbstractCommand
{
    public string $commandName = 'show:problems';

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'problemNumber',
            'n',
            InputTypeEnum::INT,
            'Number of problems to show'
        );
    }

    /**
     * @inheritDoc
     */
    public function handle(): void
    {
        echo 'test';
        // TODO: Implement handle() method.
    }
}