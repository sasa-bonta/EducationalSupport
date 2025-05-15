<?php

namespace src;

use src\Enums\InputTypeEnum;

class ShowProblemsCommand extends AbstractCommand
{
    public string $commandName = 'show:problems';

    public function configure(): void
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
        echo $this->getOption('test');
        // TODO: Implement handle() method.
    }
}