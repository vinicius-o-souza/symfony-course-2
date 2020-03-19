<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testSomething()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/security');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Hello")')->count()
        );
    }
}
