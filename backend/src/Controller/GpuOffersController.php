<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GpuOffersController extends AbstractController
{
    #[Route('/gpu/offers', name: 'app_gpu_offers')]
    public function index(): Response
    {
        return $this->render('gpu_offers/index.html.twig');
    }
}
