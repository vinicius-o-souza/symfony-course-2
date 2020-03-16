<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{
    /**
     * @Route("/form", name="form")
     */
    public function index(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $videos = $entityManager->getRepository(Video::class)->findAll();
        dump($videos);
        
        $video = new Video();
        $video->setTitle('asddas');
        $video->setCreatedAt(new \DateTime('tomorrow'));
        
        // $video = $entityManager->getRepository(Video::class)->find(1);
        
        $form = $this->createForm(VideoFormType::class, $video);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            $fileName = sha1(random_bytes(14)). '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('videos_directory'),
                $fileName
            );
            $video->setFile($fileName);
            $entityManager->persist($video);
            $entityManager->flush();
            return $this->redirectToRoute('form');
        }
        
        return $this->render('form/index.html.twig', [
            'controller_name' => 'FormController',
            'form' => $form->createView()
        ]);
    }
}
