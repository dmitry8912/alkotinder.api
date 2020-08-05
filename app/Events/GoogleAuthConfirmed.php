<?php

namespace App\Events;

use Illuminate\Auth\Events\Registered;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GoogleAuthConfirmed implements ShouldBroadcast
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public $token;
    public function __construct($token)
    {
        $this->token = $token;
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
        return new Channel('auth');
    }

    public function broadcastAs(){
        return 'authBroadcaster';
    }
}
