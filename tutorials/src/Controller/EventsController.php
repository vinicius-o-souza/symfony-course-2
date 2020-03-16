<?php

namespace App\Controller;

use App\Events\VideoCreatedEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Annotation\Route;

class EventsController extends AbstractController
{
    private $dispatcher;
    
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
    
    /**
     * @Route("/events", name="events")
     */
    public function index()
    {
        $video = new \stdClass();
        $video->title = 'Funny movie';
        $video->category = 'Funny';
        
        $event = new VideoCreatedEvent($video);
        $this->dispatcher->dispatch($event, 'video.created.event');
        
        return $this->render('events/index.html.twig', [
            'controller_name' => 'EventsController',
        ]);
    }
}
