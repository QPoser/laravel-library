<?php

namespace App\Listeners\Library;

use App\Events\Library\BundleCreated;
use App\Notifications\BundleCreatedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BundleCreatedListener
{
    public function handle(BundleCreated $event)
    {
        $bundle = $event->bundle;
        foreach ($bundle->user->subscribers as $subscriber) {
            $subscriber->notify(new BundleCreatedNotification($bundle));
        }
    }
}
