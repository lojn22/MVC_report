<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProjControllerJson extends AbstractController
{
    #[Route('api/proj/', name: 'api_proj')]
    public function proj(

    ): JsonResponse {

    }
} 