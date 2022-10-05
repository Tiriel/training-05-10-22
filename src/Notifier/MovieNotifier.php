<?php

namespace App\Notifier;

use App\Notifier\Factory\ChainNotificationFactory;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class MovieNotifier
{
    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    public function sendNotification(string $subject)
    {
        $channel = 'slack';
        $notification = new Notification();

        $this->notifier->send($notification, new Recipient('email@email.com'));
    }
}