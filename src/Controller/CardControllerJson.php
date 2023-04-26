<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardControllerJson
{
    // #[Route("/api", name: "api")]
    // public function api(): Response
    // {
    //     return $this->render('card/api.html.twig');
    // }

    #[Route("/api/deck", name: "api_deck")]
    public function jsonNumber(): Response
    {
        $deck = [
            'h1' => '♥️A', 'h2' => '♥️2', 'h3' => '♥️3', 'h4' => '♥️4', 'h5' => '♥️5', 'h6' => '♥️6', 'h7' => '♥️7', 'h8' => '♥️8', 'h9' => '♥️9', 'h10' => '♥️10', 'h11' => '♥️J', 'h12' => '♥️Q', 'h13' => '♥️K',
            'd1' => '♦️A', 'd2' => '♦️2', 'd3' => '♦️3', 'd4' => '♦️4', 'd5' => '♦️5', 'd6' => '♦️6', 'd7' => '♦️7', 'd8' => '♦️8', 'd9' => '♦️9', 'd10' => '♦️10', 'd11' => '♦️J', 'd12' => '♦️Q', 'd13' => '♦️K',
            's1' => '♠️A', 's2' => '♠️2', 's3' => '♠️3', 's4' => '♠️4', 's5' => '♠️5', 's6' => '♠️6', 's7' => '♠️7', 's8' => '♠️8', 's9' => '♠️9', 's10' => '♠️10', 's11' => '♠️J', 's12' => '♠️Q', 's13' => '♠️K',
            'c1' => '♣️A', 'c2' => '♣️2', 'c3' => '♣️3', 'c4' => '♣️4', 'c5' => '♣️5', 'c6' => '♣️6', 'c7' => '♣️7', 'c8' => '♣️8', 'c9' => '♣️9', 'c10' => '♣️10', 'c11' => '♣️J', 'c12' => '♣️Q', 'c13' => '♣️K',
        ];

        $values =[];
        foreach ($deck as $key => $value){
            $values[]= $value;}

            $data = [
                'Kortlek' => $values
            ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api_shuffle")]
        public function jsonShuffle(): Response
        {
            $deck =
            $shuffleDeck = shuffle($this->deck);

        $data = [
            'Blandad kortlek' => $shuffleDeck,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
        return $response;
        }
    
        #[Route("/api/deck/draw", name: "api_draw", methods: ['POST'] )]
        public function jNumber(): Response
        {
        $data = [
            'Citat' => "draw",
        ];
            $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
        return $response;
        }
    #[Route("/api/deck/draw/num<\d+>", name: "api_number", methods: ['POST'])]
        public function jsNumber(int $num): Response
        {$data = [
            'Citat' => "draw + num",
        ];
            $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
        return $response;
    }
}
