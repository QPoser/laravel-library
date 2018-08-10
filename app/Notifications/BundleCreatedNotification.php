<?php

namespace App\Notifications;

use App\Entities\Library\Book\Bundle;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class BundleCreatedNotification extends Notification
{
    use Queueable, SerializesModels;

    /**
     * @var Bundle
     */
    private $bundle;

    public function __construct(Bundle $bundle)
    {
        $this->bundle = $bundle;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New bundle ' . $this->bundle->title . ' created!')
            ->greeting('Hi!')
            ->line('New bundle from ' . $this->bundle->user->name)
            ->action('View bundle', route('library.bundles.show', $this->bundle))
            ->line('Thank you for using our application!');
    }
}
