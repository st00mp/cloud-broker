<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {

        $message = 'BIENVENUE DANS UN NOUVEAU RABBIT HOLE OHOHHHH :DDDDDD';

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'message' => $message
        ]);
    }
}
