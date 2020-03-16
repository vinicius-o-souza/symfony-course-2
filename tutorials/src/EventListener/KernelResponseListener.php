<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpFoundation\Response;

class KernelResponseListener
{
    public function onKernelResponse(ResponseEvent $event)
    {
        $response = new Response('dupa');
        $event->setResponse($response);
    }
}