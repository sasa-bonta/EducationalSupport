<?php

namespace src;

use src\Command\AbstractCommand;

class Application
{
    /** @var array<string, AbstractCommand> */
    private array $commands = [];

    public function registerCommand(AbstractCommand $command): void
    {
        $this->commands[$command->getCommandName()] = $command;
    }

    public function run(int $argc, array $argv): void
    {
        /** @var string $commandName */
        $commandName = $argv[1];
        try {
            if ($command = $this->commands[$commandName] ?? null) {
                $command->configure();
                $command->parseArguments($argc, $argv);
                $command->handle();
            } else {
                if (!$commandName) {
                    throw new \Exception('No command specified.');
                } else {
                    throw new \Exception('Command does not exist.');
                }
            }
        } catch (\Throwable $exception) {
            echo sprintf("\e[1;37;42m%s\e[0m\n", $exception->getMessage());
        }
    }
}