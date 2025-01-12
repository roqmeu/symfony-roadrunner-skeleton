<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Messenger;

class SimpleEvent
{
    public function __construct(
        readonly private string $text,
    ) {
    }

    public function getText(): string
    {
        return $this->text;
    }
}
