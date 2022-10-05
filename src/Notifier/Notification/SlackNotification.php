<?php

namespace App\Notifier\Notification;

use Symfony\Component\Notifier\Bridge\Slack\Block\SlackActionsBlock;
use Symfony\Component\Notifier\Bridge\Slack\Block\SlackDividerBlock;
use Symfony\Component\Notifier\Bridge\Slack\Block\SlackSectionBlock;
use Symfony\Component\Notifier\Bridge\Slack\SlackOptions;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Notification\ChatNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

class SlackNotification extends Notification implements ChatNotificationInterface
{
    private string $messageSubject;

    public function __construct(string $messageSubject, string $subject = '', array $channels = [])
    {
        parent::__construct($subject, $channels);
        $this->messageSubject = $messageSubject;
    }

    public function asChatMessage(RecipientInterface $recipient, string $transport = null): ?ChatMessage
    {
        $message = ChatMessage::fromNotification($this, $recipient, $transport);
        $message->subject($this->messageSubject);
        $message->options((new SlackOptions())
            ->iconEmoji('tada')
            ->username('Sf5Formation')
            ->block((new SlackSectionBlock())->text($this->messageSubject))
            ->block((new SlackDividerBlock()))
            ->block((new SlackSectionBlock())
                ->text('This is a notification that works!')
            )
            ->block((new SlackActionsBlock())
                ->button('Yay', 'http://localhost:8000', 'primary')
            )
        );

        return $message;

    }
}