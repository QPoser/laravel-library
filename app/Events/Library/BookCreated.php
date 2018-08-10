<?php

namespace App\Events\Library;

use App\Entities\Library\Book;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BookCreated
{
    use Dispatchable, SerializesModels;

    /**
     * @var Book
     */
    public $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }
}
