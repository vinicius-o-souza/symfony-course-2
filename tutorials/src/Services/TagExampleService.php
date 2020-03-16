<?php

namespace App\Services;

use Doctrine\ORM\Event\PostFlushEventArgs;

class TagExampleService
{
    public function __construct()
    {
        dump('Construct');
    }
    
    public function postFlush(PostFlushEventArgs $args)
    {
        dump('postFlush');
        dump($args);
    }
    
    public function clear()
    {
        dump('clearrrr');
    }
}