<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardControllerJson
{
    #[Route("/api/deck", name: "api_deck")]
    public function jsonDeck(
        Request $request,
        SessionInterface $session
    ): Response
    {
        if($session->get("deck_of_cards")) {
            $deck = $session->get("deck_of_cards");
        } else {
            $deck = new DeckOfCards();
        }
        
        $data = [
            "Kortlek" => $deck->getDeck(),
        ];
        $session->set("deck_of_cards", $deck);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api_shuffle")]
        public function jsonShuffle(
            Request $request,
            SessionInterface $session
        ): Response
        {
            $deck = $session->get("deck_of_cards");
            $deck->deckshuffle();
    
            $data = [
                "deck" => $deck->getDeck()
            ];
            $session->set("deck_of_cards", $deck);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
        return $response;
        }

    #[Route("/api/deck/draw", name: "api_draw")]
    public function jsonDraw(
        Request $request,
        SessionInterface $session
        ): Response
        {
            $deckOfCards = $session->get("deck_of_cards");
            $card = $deckOfCards->drawCard();
            $drawCard = $session->set("deck_of_cards", $deckOfCards);
            $cardsLeft = count($deckOfCards->getDeck());
            
            
            $data = [
                "card" => $card,
                "card_left" => $cardsLeft
            ];
            
            $response = new JsonResponse($data);
            $response->setEncodingOptions(
                $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
            return $response;
        }

    #[Route("/api/deck/draw/{num<\d+>}", name: "api_number")]
        public function jsonDrawMany(
            int $num,
            Request $request,
            SessionInterface $session
        ): Response
        {
                if ($num > 52) {
                        throw new \Exception("Can not draw more than 52 cards!");
                    }
            
                    $deckOfCards = $session->get("deck_of_cards");
                    $card = $deckOfCards->drawManyCards($num);
                    $drawCard = $session->set("deck_of_cards", $deckOfCards);
                    $cardsLeft = count($deckOfCards->getDeck());
            
                    $data = [
                        "cards" => $card,
                        "card_left" => $cardsLeft
                    ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
        return $response;
        }

    #[Route("/api/init", name: "api_card_init_get", methods: ['GET'])]
        public function init(): Response
        {
            // return $this->render('card/init.html.twig');
            $response = new JsonResponse();
            $response->setEncodingOptions(
                $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
            return $response;
        }

    #[Route("/api/init", name: "api_card_init_post", methods: ['POST'])]
        public function jsonDrawNumber(
            int $num,
            Request $request,
            SessionInterface $session
        ): Response
        {
            $numCards = $request->request->get('num_cards');
            $hand = new CardHand();
            for ($i = 1; $i <= $numDice; $i++) {
                $hand->add(new DeckOfCards());
            }
            $hand->drawCard();
            
            $session->set("card_hand", $hand);
            $session->set("cards", $numCards);
            
            $response = new JsonResponse($data);
            $response->setEncodingOptions(
                $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
            return $response;
        }

}
