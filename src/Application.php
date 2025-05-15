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
            try {
                $command->parseArguments($argc, $argv);
                $command->handle();
            } catch (\Throwable $exception) {
                echo sprintf("\e[1;37;42m%s\e[0m\n", $exception->getMessage());
            }
        } else {
            echo 'No command specified.' . PHP_EOL;
        }
    }
}