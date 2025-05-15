<?php

namespace src;

use src\Enums\InputTypeEnum;

class InputOption
{
    public function __construct(
        public string        $name,
        public string        $shortcut,
        public InputTypeEnum $type,
        public string        $description,
        public bool          $required,
        public string|int|null        $value = null,
    )
    {
    }
}