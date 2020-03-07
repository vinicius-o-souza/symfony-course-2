<?php

namespace App\Controller;

use App\Entity\User;
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
     * @Route("/default", name="default")
     */
    public function index(GiftsService $gifts, Request $request, SessionInterface $session)
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        
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
            'users' => $users,
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
     * @Route("/url-to-redirect/{param?}, name="route_to_redirect")
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
    
}
