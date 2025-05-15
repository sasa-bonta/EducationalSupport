<?php

namespace src;

class Application
{
    /** @var array<string, AbstractCommand>  */
    private array $commands = [];

    public function registerCommand(AbstractCommand $command): void
    {
        $this->commands[$command->getCommandName()] = $command;
    }

    public function run(int $argc, array $argv): void
    {
        /** @var string $commandName */
        $commandName = $argv[1];
        if ($command = $this->commands[$commandName] ?? null) {
            $command->configure();
            $command->parseArguments($argc, $argv);
            $command->handle();
        } else {
            echo 'No command specified.' . PHP_EOL;
        }
    }
}