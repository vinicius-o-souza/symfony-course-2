<?php

namespace App\Tests;

trait RoleAdmin
{
    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'jw@symf4.loc',
            'PHP_AUTH_PW' => 'passw'
        ]);
        $this->client->disableReboot();

        $this->entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        // self::ensureKernelShutdown();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();    
        $this->entityManager = null; // avoid memory leaks   
    } 
}