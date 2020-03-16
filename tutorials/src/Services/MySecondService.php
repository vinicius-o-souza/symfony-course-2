<?php

namespace App\Services;

class MySecondService implements ServiceInterface
{
    public function __construct()
    {
        dump('param');
        // $this->doSomething();
    }
    
    public function doSomething()
    {
        
    }
    
    public function doSomething2()
    {
        return 'wow!';
    }
}