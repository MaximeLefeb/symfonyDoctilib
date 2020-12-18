<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController {
    /**
     * @Route("/main", name="main")
     */
    public function index(): Response {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
