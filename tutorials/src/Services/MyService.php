<?php

namespace App\Services;

class MyService 
{
    
    use OptionalServiceTrait;
    
    public $logger;
    public $my;
    
    public function __construct($second_service)
    {
        dump($second_service);
    }
    
    public function someAction()
    {
        dump($this->service->doSomething2());
        dump($this->my);
        dump($this->my->doSomething2());
        dump($this->logger);
    }
    
}