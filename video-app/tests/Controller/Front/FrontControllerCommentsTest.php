<?php

namespace App\Tests;

use App\Tests\Rollback;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerCommentsTest extends WebTestCase
{
    use Rollback;
    
    public function testNotLoggedInUser()
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->followRedirects();
        
        $crawler = $client->request('GET', '/video-details/16');
        
        $form = $crawler->selectButton('Add')->form([
            'comment' => 'Test Comment'
        ]);
        $client->submit($form);
        
        $this->assertContains('Please sign in', $client->getResponse()->getContent());
    }
    
    public function testNewCommentAndNumberOfComments()
    {
        $this->client->followRedirects();
        
        $crawler = $this->client->request('GET', '/video-details/16');
        
        $form = $crawler->selectButton('Add')->form([
            'comment' => 'Test Comment'
        ]);
        $this->client->submit($form);
        
        $crawler = $this->client->request('GET', '/video-list/category/toys,2');
        
        $this->assertSame('Comments (1)', $crawler->filter('a.ml-1')->text());
    }
}
