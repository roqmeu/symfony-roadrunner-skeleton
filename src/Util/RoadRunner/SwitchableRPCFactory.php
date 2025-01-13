<?php

declare(strict_types=1);

namespace App\Util\RoadRunner;

use Baldinof\RoadRunnerBundle\Helpers\RPCFactory;
use Spiral\Goridge\RPC\RPCInterface;
use Spiral\RoadRunner\EnvironmentInterface;

class SwitchableRPCFactory
{
    public static function fromEnvironment(EnvironmentInterface $environment): ?RPCInterface
    {
        return null !== ($_SERVER['RR_MODE'] ?? null) ? RPCFactory::fromEnvironment($environment) : null;
    }
}
