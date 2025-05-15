<?php

namespace src\Command;

use src\Enums\InputTypeEnum;
use src\InputOption;

abstract class AbstractCommand
{
    protected string $commandName;
    /** @var array<string, InputOption> */
    private array $options = [];

    abstract public function configure(): void;

    abstract public function handle(): void;

    public function getCommandName(): string
    {
        return $this->commandName;
    }

    public function addOption(string $name, string $shortCut, InputTypeEnum $type, string $description, bool $required = false): self
    {
        $this->options[$name] = new InputOption(
            $name,
            $shortCut,
            $type,
            $description,
            $required
        );

        return $this;
    }

    public function getOption(string $name): string|int|null
    {
        if ($option = $this->options[$name] ?? null) {
                return match ($option->type) {
                    InputTypeEnum::STRING => (string) $option->value,
                    InputTypeEnum::INT => (int) $option->value,
                };
        }

        throw new \Exception("Argument option '{$name}' is doesn't exist.");
    }

    public function parseArguments(int $argc, array $argv): void
    {
        for ($i = 0; $i < $argc; $i += 2) {
            if (isset($argv[$i + 1])) {
                if ($option = $this->getInputOptionByShortcut($argv[$i])) {
                    $this->options[$option->name]->value = $argv[$i + 1];
                }
            }
        }

        $emptyRequiredOptions = array_filter($this->options, fn (InputOption $option) => $option->required === true && $option->value === null);

        foreach ($emptyRequiredOptions as $option) {
            throw new \Exception(sprintf("Option '%s' cannot be empty.", $option->name));
        }
    }

    private function getInputOptionByShortcut(string $shortcut): ?InputOption
    {
        $shortcut = trim($shortcut, "-");
        return array_find($this->options, fn(InputOption $option) => $option->shortcut === $shortcut);
    }
}