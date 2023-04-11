<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuoteControllerJson
{
    #[Route("/api/quote")]
    public function jsonNumber(): Response
    {
        $number = random_int(0, 2);

        // Tidszonen
        date_default_timezone_set('Europe/Stockholm');
    
        // Dagens datum
        $today = date('Y-m-d H:i:s');

        // Citat
        $citat = array(
            'Det är bättre att vara tyst och betraktad som en dåre än att tala och ta bort alla tvivel.', 
            'En pessimist är en person som tvingats lyssna på för många optimister.',
            'Jag lagar mat med vin, ibland tillsätter jag det till och med i maten.'
        );

        $randomCitat = array_rand($citat,1);
        // print_r($randomCitat);
        // echo $citat[$randomCitat[0]];

        $data = [
            'Dagens datum' => $today,
            // 'Citat' => $randomCitat,
            'Citat' => $citat[$number],
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
        return $response;
    }
}
