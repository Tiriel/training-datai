<?php

namespace App\Notifier;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When('dev')]
#[AsDecorator(decorates: SensioTvNotifier::class)]
class TraceableNotifier implements NotifierInterface
{
    public function __construct(
        private readonly NotifierInterface $notifier,
        private readonly LoggerInterface $logger,
    ) {}

    public function sendNotification(string $subject): void
    {
        $this->logger->log('info', 'Sending notification');

        $this->notifier->sendNotification($subject);
    }
}
