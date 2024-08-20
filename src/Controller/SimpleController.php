<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SimpleController extends AbstractController
{
    #[Route('/simple', name: 'simple')]
    public function index(): Response
    {
        return new Response('Hello World!');
    }
}
