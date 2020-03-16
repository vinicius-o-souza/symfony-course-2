<?php

namespace App\Events;

use Symfony\Contracts\EventDispatcher\Event;

class VideoCreatedEvent extends Event
{
    private $video;
    
    public function __construct($video)
    {
        $this->video = $video;
    }
    
    public function getVideo()
    {
        return $this->video;
    }
}