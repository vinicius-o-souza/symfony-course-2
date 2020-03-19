<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslationController extends AbstractController
{
    /**
     * @Route({
     *      "en": "/transaction",
     *      "pl": "/trys"
     * }, name="translation")
     */
    public function index(TranslatorInterface $translator)
    {
        $translated = $translator->trans('some.key');
        dump($translated);

        return $this->render('translation/index.html.twig', [
            'controller_name' => 'TranslationController',
        ]);
    }
}
