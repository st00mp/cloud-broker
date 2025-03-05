<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowController extends AbstractController
{
    #[Route(path: '/show', name: 'show')]
    public function show(): Response
    {
        $products = [
            ["nom" => "product 1", "prix" => 10],
            ["nom" => "product 2", "prix" => 15],
            ["nom" => "product 3", "prix" => 20],
            ["nom" => "product 4", "prix" => 25],
            ["nom" => "product 5", "prix" => 30],
            ["nom" => "product 6", "prix" => 35],
        ];

        $randomInt = [];
        for ($i = 0; $i < 10; $i++) {
            $randomInt[$i] = random_int(0, 100);
        }

        return $this->render('/basic/show.html.twig', [
            'products' => $products,
            'randomInt' => $randomInt
        ]);
    }
}
