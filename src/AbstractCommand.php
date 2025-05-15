<?php

namespace src;

use src\Enums\InputTypeEnum;

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

    public function addOption(string $name, string $shortCut, InputTypeEnum $type, string $description): self
    {
        $this->options[$name] = new InputOption(
            $name,
            $shortCut,
            $type,
            $description,
            null
        );

        return $this;
    }

    public function getOption(string $name): string|int|null
    {
        foreach ($this->options as $option) {
            if ($option->name === $name) {
                return match ($option->type) {
                    InputTypeEnum::STRING => (string) $option->value,
                    InputTypeEnum::INT => (int) $option->value,
                };
            }
        }

        return null;
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
    }

    private function getInputOptionByShortcut(string $shortcut): ?InputOption
    {
        $shortcut = trim($shortcut, "-");
        return array_find($this->options, fn(InputOption $option) => $option->shortcut === $shortcut);
    }
}