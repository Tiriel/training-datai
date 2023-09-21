<?php

namespace App\Notifier;

use App\Notifier\NotifierInterface as BaseNotifierInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class SensioTvNotifier implements BaseNotifierInterface
{
    public function __construct(
        private readonly NotifierInterface $notifier,
        #[TaggedIterator('app.notification_factory', defaultIndexMethod: 'getIndex')]
        private iterable $factories,
    ) {
        $this->factories = $factories instanceof \Traversable ? iterator_to_array($factories) : $factories;
    }

    public function sendNotification(string $subject): void
    {
        $user = new class {
            public function getEmail(): string
            {
                return 'me@me.com';
            }

            public function getPreferredChannel(): string
            {
                return 'slack';
            }
        };

        $notification = $this->factories[$user->getPreferredChannel()]->create($subject);

        $this->notifier->send($notification, new Recipient($user->getEmail()));
    }
}
