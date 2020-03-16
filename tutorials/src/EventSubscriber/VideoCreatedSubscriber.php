<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class VideoCreatedSubscriber implements EventSubscriberInterface
{
    public function onVideoCreatedEvent($event)
    {
        dump('subscriber: '. $event->getVideo()->title);
    }
    
    public function onKernelResponse1(ResponseEvent $event)
    {
        $response = new Response('dupa1');
        dump('111111111111111111111');
        // $event->setResponse($response);
    }
    
    public function onKernelResponse2(ResponseEvent $event)
    {
        $response = new Response('dupa2');
        dump('2222222222222222222222');
        // $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            // 'video.created.event' => 'onVideoCreatedEvent',
            // KernelEvents::RESPONSE => [
            //     ['onKernelResponse1', 1],
            //     ['onKernelResponse2', 2]
            // ]
        ];
    }
}
