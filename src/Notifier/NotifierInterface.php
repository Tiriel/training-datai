<?php

namespace App\Notifier;

use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(SensioTvNotifier::class)]
interface NotifierInterface
{
    public function sendNotification(string $subject): void;
}
