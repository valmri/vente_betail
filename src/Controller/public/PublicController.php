<?php

namespace App\Controller\Public;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PublicController extends AbstractController
{
    #[Route('/', name: 'public_accueil')]
    public function index(): Response
    {
        return $this->render('public/accueil/index.html.twig', [
            'controller_name' => 'PublicController',
        ]);
    }
}
