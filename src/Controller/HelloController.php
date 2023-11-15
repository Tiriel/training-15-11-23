<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    #[Route('/hello/{name?World}', name: 'app_hello_index', requirements: ['name' => '(?:\pL|[- ])+'])]
    public function index(string $name): Response
    {
        return $this->render('hello/index.html.twig', [
            'controller_name' => $name,
        ]);
    }
}
