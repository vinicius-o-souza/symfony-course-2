<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\File;
use App\Entity\Music;
use App\Entity\Pdf;
use App\Entity\User;
use App\Services\MyService;
use App\Services\ServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Annotation\Route;

class Default2Controller extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;        
    }
    
    /**
     * @Route("/services-interface", name="services-interface")
     */
    public function servicesInterface(ServiceInterface $service, ContainerInterface $container)
    {
        // dump($service);
        // $myService->someAction();
        
        return $this->render('default2/index.html.twig', [
            'controller_name' => 'Default2Controller',
        ]);
    }
    
    /**
     * @Route("/tag", name="tag")
     */
    public function tagexample()
    {
        $user = $this->entityManager->getRepository(User::class)->find(1);
        $user->setName('Rob');
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $this->render('default2/index.html.twig', [
            'controller_name' => 'Default2Controller',
        ]);
    }
    
    /**
     * @Route("/services", name="services")
     */
    public function services(MyService $myService, ContainerInterface $container)
    {
        dump($container->get('app.myservice'));
        // $myService->someAction();
        
        return $this->render('default2/index.html.twig', [
            'controller_name' => 'Default2Controller',
        ]);
    }
    
    /**
     * @Route("/polimorphic", name="polimorphic")
     */
    public function polimorphic()
    {
        $pdf = $this->entityManager->getRepository(Pdf::class)->findAll();
        $music = $this->entityManager->getRepository(Music::class)->findAll();
        $file = $this->entityManager->getRepository(File::class)->findAll();
        
        // dump($pdf);
        // dump($music);
        // dump($file);
        $repository = $this->entityManager->getRepository(Author::class);
        $author = $repository->findByIdWithPdf(1);
        
        foreach ($author->getFiles() as $file) {
            dump($file);
        }
        
        return $this->render('default2/index.html.twig', [
            'controller_name' => 'Default2Controller',
        ]);
    }
    
    /**
     * @Route("/eager-loading", name="eager-loading")
     */
    public function eagerLoading()
    {
        // $user = new User();
        // $user->setName('Robert');
        
        // for ($i = 0; $i < 5; $i++) {
        //     $video = new Video();
        //     $video->setTitle('Video title - '. $i);
        //     $user->addVideo($video);
        //     $this->entityManager->persist($video);
        // }
        // $this->entityManager->persist($user);
        // $this->entityManager->flush();
        
        $user = $this->entityManager->getRepository(User::class)->findWithVideos(1);
        
        return $this->render('default2/index.html.twig', [
            'controller_name' => 'Default2Controller',
        ]);
    }
}
