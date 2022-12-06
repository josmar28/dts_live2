<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentNotif implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $section_to;
    public $released_by;
    public $section_released_by;
    public $route_no;

    public function __construct($section_to,$released_by,$section_released_by,$route_no)
    {
        $this->section_to = $section_to;
        $this->released_by = $released_by->lname.', '.$released_by->fname.' '.$released_by->mname;
        $this->section_released_by = $section_released_by->description;
        $this->route_no = $route_no;
    }

    public function broadcastAs()
    {
        return 'document_event';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('document_channel');
    }
}
