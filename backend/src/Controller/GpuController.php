<?php

namespace App\Controller;

use App\Repository\InstanceSpotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Exemple twig classique (statique)
final class GpuController extends AbstractController
{
    #[Route('/offers', name: 'app_gpu')]
    public function list(InstanceSpotRepository $repo): Response
    {
        $offers = $repo->findAll();

        return $this->render('gpu/index.html.twig', [
            'offers' => $offers,
        ]);
    }
}
