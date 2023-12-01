<?php

namespace App\Listeners;

use App\Events\OurExampleEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class OurExampleListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * @param OurExampleEvent $event
     */
    public function handle(OurExampleEvent $event): void
    {
        Log::debug("L'utilisateur $event->username vient d'effectuer l'action $event->action");
    }
}
