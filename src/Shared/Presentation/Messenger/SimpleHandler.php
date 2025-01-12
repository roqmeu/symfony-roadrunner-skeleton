<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Messenger;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SimpleHandler
{
    public function __construct(
        readonly private LoggerInterface $logger,
    ) {
    }

    public function __invoke(SimpleEvent $event): void
    {
        $this->logger->notice('SimpleEvent received: ' . $event->getText());
    }
}
