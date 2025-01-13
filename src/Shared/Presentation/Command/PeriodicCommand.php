<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Scheduler\Attribute\AsCronTask;

#[AsCommand(name: 'app:shared:periodic')]
#[AsCronTask(expression: '* * * * *', arguments: 'first --option=second', transports: 'amqp')]
class PeriodicCommand extends Command
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('argument', InputArgument::OPTIONAL);
        $this->addOption('option', null, InputOption::VALUE_OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->notice(
            "I am periodic command! Argument = \"{$input->getArgument('argument')}\"; Option = \"{$input->getOption('option')}\""
        );

        return self::SUCCESS;
    }
}
