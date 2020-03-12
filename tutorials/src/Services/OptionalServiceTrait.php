<?php

namespace App\Services;

trait OptionalServiceTrait
{
    
    private $service;
    
    /**
     * @required
     */
    public function setSecondService(MySecondService $second_service)
    {
        $this->service = $second_service;
    }
    
}