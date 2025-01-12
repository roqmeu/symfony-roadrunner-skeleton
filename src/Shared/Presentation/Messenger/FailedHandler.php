<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Messenger;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class FailedHandler
{
    public function __construct(
        readonly private LoggerInterface $logger,
    ) {
    }

    public function __invoke(FailedEvent $event): void
    {
        $this->logger->notice('FailedEvent received: ' . $event->getText());

        throw new \RuntimeException('Expected error');
    }
}
