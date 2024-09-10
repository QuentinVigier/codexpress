<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/profile', name: 'profile')]
    public function profile(): Response
    {
        return $this->render('home/profile.html.twig', [
        ]);
    }



    #[Route('/notifications', name: 'notifications')]
    public function notifications(): Response
    {
        return $this->render('home/notifications.html.twig', [
        ]);
    }

    #[Route('/categories', name: 'categories')]
    public function categories(): Response
    {
        return $this->render('home/categories.html.twig', [
        ]);
    }
}
