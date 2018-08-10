<?php

namespace App\Events\Library;

use App\Entities\Library\Book\Bundle;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BundleCreated
{
    use Dispatchable, SerializesModels;

    /**
     * @var Bundle
     */
    public $bundle;

    public function __construct(Bundle $bundle)
    {
        $this->bundle = $bundle;
    }
}
