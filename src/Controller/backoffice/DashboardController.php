<?php

namespace App\Controller\backoffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'backoffice_dashboard')]
    public function index(): Response
    {
        return $this->render('backoffice/dashboard/index.html.twig');
    }
}
