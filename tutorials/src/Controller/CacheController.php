<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Component\Routing\Annotation\Route;

class CacheController extends AbstractController
{
    /**
     * @Route("/cache-tag", name="cache-tag")
     */
    public function cacheTag()
    {
        $cache = new TagAwareAdapter(new FilesystemAdapter());
        
        $acer = $cache->getItem('acer');
        $dell = $cache->getItem('dell');
        $apple = $cache->getItem('apple');
        
        if (!$acer->isHit()) {
            $acer_from_db = 'acer laptop';
            $acer->set($acer_from_db);
            $acer->tag(['computer', 'laptops', 'acer']);
            $cache->save($acer);
            dump('acer laptop from database');
        }
        
        if (!$dell->isHit()) {
            $dell_from_db = 'dell laptop';
            $dell->set($dell_from_db);
            $dell->tag(['computer', 'laptops', 'dell']);
            $cache->save($dell);
            dump('dell laptop from database');
        }
        
        if (!$apple->isHit()) {
            $apple_from_db = 'apple laptop';
            $apple->set($apple_from_db);
            $apple->tag(['computer', 'desktops', 'apple']);
            $cache->save($apple);
            dump('apple laptop from database');
        }
        
        // $cache->invalidateTags(['dell']);
        
        dump($acer->get());
        dump($dell->get());
        dump($apple->get());
        
        return $this->render('cache/index.html.twig', [
            'controller_name' => 'CacheController',
        ]);
    }
    
    /**
     * @Route("/cache", name="cache")
     */
    public function index()
    {
        $cache = new FilesystemAdapter();
        $posts = $cache->getItem('database.get_posts');
        if (!$posts->isHit()) {
            $posts_from_db = ['post 1', 'post 2', 'post 3'];
            dump('connected with database ...');
            
            $posts->set(serialize($posts_from_db));
            $posts->expiresAfter(5);
            $cache->save($posts);
        }
        $cache->deleteItem('database.get_posts');
        $cache->clear();
        dump(unserialize($posts->get()));
        
        return $this->render('cache/index.html.twig', [
            'controller_name' => 'CacheController',
        ]);
    }
}
