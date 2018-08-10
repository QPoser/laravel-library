<?php

namespace App\Notifications;

use App\Entities\Library\Book;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class BookCreatedNotification extends Notification
{
    use Queueable, SerializesModels;

    /**
     * @var Book
     */
    private $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New book ' . $this->book->title . ' created!')
            ->greeting('Hi!')
            ->line('New book from ' . $this->book->user->name)
            ->action('View book', route('library.books.show', $this->book))
            ->line('Thank you for using our application!');
    }
}
