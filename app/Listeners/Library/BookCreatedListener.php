<?php

namespace App\Listeners\Library;

use App\Events\Library\BookCreated;
use App\Notifications\BookCreatedNotification;

class BookCreatedListener
{
    public function handle(BookCreated $event)
    {
        $book = $event->book;
        foreach ($book->user->subscribers as $subscriber) {
            $subscriber->notify(new BookCreatedNotification($book));
        }
    }
}
