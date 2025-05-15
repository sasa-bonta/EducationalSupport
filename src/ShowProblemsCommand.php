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
            'Number of problems to show',
            true
        );
    }

    /**
     * @inheritDoc
     */
    public function handle(): void
    {
        echo $this->getOption('problemNumber');
        // TODO: Implement handle() method.
    }
}