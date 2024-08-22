<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerTwig extends AbstractController
{
    #[Route('/', name: 'start')]
    public function start(): Response
    {
        return $this->render('start.html.twig');
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route('/report', name: 'report')]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    #[Route('/lucky', name: 'lucky')]
    public function number(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'number' => $number,
        ];

        return $this->render('lucky.html.twig', $data);
    }
}
