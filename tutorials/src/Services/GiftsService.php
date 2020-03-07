<?php

namespace App\Services;

use Psr\Log\LoggerInterface;

class GiftsService
{
    public $gifts = ['flowers', 'car', 'piano', 'money'];
    
    public function __construct(LoggerInterface $logger)
    {
        $logger->info('Gift were randomized!');
        shuffle($this->gifts);
    }
}