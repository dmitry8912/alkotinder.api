<?php

namespace App\Events;

use Illuminate\Auth\Events\Registered;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DrinkAdded implements ShouldBroadcast
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public $drink;
    public function __construct($drink)
    {
        $this->drink = $drink;
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        //
    }

    public function broadcastOn()
    {
        return new Channel('drinkUpdates');
    }

    public function broadcastAs(){
        return 'drinkBroadcaster';
    }
}
