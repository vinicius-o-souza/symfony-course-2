<?php

namespace App\Controller;

use App\Entity\SecurityUser;
use App\Entity\Video;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthorizationController extends AbstractController
{
    /**
     * @Route("/authorization", name="authorization")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $users = $entityManager->getRepository(SecurityUser::class)->findAll();
        dump($users);

        $user = new SecurityUser();
        $user->setEmail('admin@admin.com');
        $password = $passwordEncoder->encodePassword($user, '123456789');
        $user->setPassword($password);
        $user->setRoles(['ROLE_ADMIN']);

        $video = new Video();
        $video->setTitle('video title');
        $video->setFile('video.path');
        $video->setCreatedAt(new \DateTime());
        $entityManager->persist($video);
        $entityManager->flush();

        $user->addVideo($video);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('authorization/index.html.twig', [
            'controller_name' => 'AuthorizationController',
        ]);
    }

    /**
     * @Route("/authorization/{id}/delete-video", name="delete-video")
     */
    public function deleteVideo(Request $request, UserPasswordEncoderInterface $passwordEncoder, Video $video)
    {
        $this->denyAccessUnlessGranted('VIDEO_DELETE', $video);

        $entityManager = $this->getDoctrine()->getManager();
        $users = $entityManager->getRepository(SecurityUser::class)->findAll();
        dump($users);
        // dump($video);

        return $this->render('authorization/index.html.twig', [
            'controller_name' => 'AuthorizationController',
        ]);
    }
}
