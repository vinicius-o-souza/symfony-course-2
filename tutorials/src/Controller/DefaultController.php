<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Video;
use App\Services\GiftsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends AbstractController
{
    
    /**
     * @Route("/test-many-to-many", name="test-many-to-many")
     */
    public function testManyToMany()
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        // for ($i = 0; $i < 5; $i++) {
        //     $user = new User();
        //     $user->setName('RObert - '. $i);
        //     $entityManager->persist($user);
        // }
        // $entityManager->flush();
        // dump($user);
        
        $users = $entityManager->getRepository(User::class)->findAll();
        
        // $users[0]->addFollowed($users[1]);
        // $users[0]->addFollowed($users[2]);
        // $users[0]->addFollowed($users[3]);
        // $users[0]->addFollowed($users[4]);
        // $users[0]->addFollowing($users[4]);
        // $entityManager->flush();
        dump($users[4]->getFollowing()->count());
        
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController'
        ]);
    }
    /**
     * @Route("/test-many-to-one", name="test-many-to-one")
     */
    public function testManyToOne()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find(2);
        
        $videos = $user->getVideos();
        
        $user->removeVideo($videos[0]);
        
        $entityManager->flush();        
        dump($user);
        
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController'
        ]);
    }
    
    /**
     * @Route("/add-video-to-user", name="add-video-to-user")
     */
    public function addVideoToUser()
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $user = new User();
        $user->setName('Vídeo Guy');
        
        for ($i = 1; $i <= 5; $i++) {
            $video = new Video();
            $video->setTitle('Video Title - '. $i);
            $user->addVideo($video);
            $entityManager->persist($video);
        }
        
        $entityManager->persist($user);
        $entityManager->flush();
        
        dump($user);
        
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController'
        ]);
    }

    /**
     * @Route("/user/{id}", name="user")
     */
    public function user(Request $request, User $user)
    {
        dump($user);
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController'
        ]);
    }
    
    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $user = new User;
        $user->setName('Robert');
        $entityManager->persist($user);
        $entityManager->flush();
        
        dump('A new user was saved with the id of ' . $user->getId());
        
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController'
        ]);
    }
    
    /**
     * @Route("/read", name="read")
     */
    public function read(Request $request) 
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneBy(['name' => 'name - 1'], ['id' => 'ASC']);
        $user = $repository->findAll();
        $user = $repository->findBy(['name' => 'name - 1'], ['id' => 'ASC']);
        dump($user);
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController'
        ]);
    }
    
    /**
     * @Route("/update", name="update")
     */
    public function update(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $id = 21;
        $user = $entityManager->getRepository(User::class)->find($id);
        
        if (!$user) {
            throw $this->createNotFoundException('No user found for id '. $id);
        }
        
        $user->setName('New user name!');
        $entityManager->flush();
        
        dump($user);
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController'
        ]);
    }
    
    /**
     * @Route("/delete", name="delete")
     */
    public function delete(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $id = 21;
        $user = $entityManager->getRepository(User::class)->find($id);
        
        $entityManager->remove($user);
        $entityManager->flush();
        
        dump($user);
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController'
        ]);
    }
    
    /**
     * @Route("/raw", name="raw")
     */
    public function rawQuery(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $conn = $entityManager->getConnection();
        $sql = '
        SELECT * FROM user u
        WHERE u.id > :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => 1]);
        
        dump($stmt->fetchAll());
        
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController'
        ]);
    }
    
    /**
     * @Route("/default", name="default")
     */
    public function index(GiftsService $gifts, Request $request, SessionInterface $session)
    {
        // $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        
        // Flash
        $this->addFlash("notice", "Your changes were saved!");
        
        // Cookies
        $cookie = new Cookie("my_cookie", "cookie value", time() + (2 * 365 * 24 * 60 *60));
        $res = new Response();
        $res->headers->setCookie($cookie);
        $res->send();
        
        // Session
        $session->set('name', 'session value');
        $session->remove('name');
        $session->clear();
        if ($session->has('name')) {
            exit($session->get('name'));
        }
        
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            // 'users' => $users,
            'random_gift' => $gifts->gifts
        ]);
    }
    
    /**
     * @Route("blog/{page?}", name="blog_list", requirements={"page"="\d+"})
     */
    public function index2()
    {
        return new Response('Optional parameters in url and requirements for parameters');
    }
    
    /**
     * @Route(
     *      "/articles/{_locale}/{year}/{slug}/{category}",
     *      defaults={"category": "computers"},
     *      requirements={
     *          "_locale": "en|fr",
     *          "category": "computers|rtv",
     *          "year": "\d+"
     *      }
     * )
     */
    public function index3()
    {
        return new Response("An advanced route example");
    }
    
    /**
     * @Route({
     *  "nl": "/over-ons",
     *  "en": "/about-us"
     * }, name="about_us")
     */
    public function index4()
    {
        return new Response("Translated routes");
    }
    
    /**
     * @Route("generate-url/{param?}", name="generate_url")
     */
    public function generate_url() 
    {
        exit($this->generateUrl(
            'generate_url',
            ['param' => 10],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }
    
    /**
     * @Route("/download")
     */
    public function download()
    {
        $path = $this->getParameter('download_directory');
        return $this->file($path. 'file.pdf');
    }
    
    /**
     * @Route("/redirect-test")
     */
    public function redirectTest()
    {
        return $this->redirectToRoute('route_to_redirect', ['param' => 10]);
    }
    
    /**
     * @Route("/url-to-redirect/{param?}", name="route_to_redirect")
     */
    public function methodToRedirect()
    {
        exit('Test redirection');
    }
    
    /**
     * @Route("/forwarding-to-controller")
     */
    public function forwardingToController()
    {
        $response = $this->forward(
            'App\Controller\DefaultController::methodToForwardTo',
            ['param' => 1]
        );
    }
    
    /**
     * @Route("/url-to-forward-to/{param?}", name="route_to_forward_to")
     */
    public function methodToForwardTo($param)
    {
        exit('Test controller forwarding - '. $param);
    }
    
    
    public function mostPopularPosts($number = 3)
    {
        $posts = ['post 1', 'post 2', 'post 3', 'post 4'];
        return $this->render('default/most_popular_posts.html.twig', [
            'posts' => $posts
        ]);
    }
    
}
