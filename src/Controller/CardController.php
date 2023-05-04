<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\DeckJoker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    #[Route("/card", name: "card")]
    public function home(): Response
    {
        return $this->render('card/home.html.twig');
    }

    #[Route("/card/deck", name: "deck")]
    public function deckCards(Request $request): Response
    {
        $session = $request->getSession();
        if($session->get("deck_of_cards")) {
            $deck = $session->get("deck_of_cards");
        } else {
            $deck = new DeckOfCards();
        }
        
        $data = [
            "deck" => $deck->getDeck(),
        ];
        $session->set("deck_of_cards", $deck);
        
        return $this->render('card/deck.html.twig', $data);
    }
    
    #[Route("/card/deck/joker", name: "joker")]
    public function deckCardsJoker(): Response
    {
        $deck = new DeckJoker();
    
        $data = [
            "deck" => $deck->getDeck(),
        ];
        
        return $this->render('card/deck.html.twig', $data);
    }
    
    #[Route("/card/deck/shuffle", name: "shuffle")]
    public function shuffleDeck(Request $request): Response
    {
        $session = $request->getSession();
        $deck = $session->get("deck_of_cards");
        $deck->deckshuffle();

        $data = [
            "deck" => $deck->getDeck()
        ];
        $session->set("deck_of_cards", $deck);

        return $this->render('card/deckshuffle.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "draw")]
    public function cardDraw(
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
        
        return $this->render('card/draw.html.twig', $data);
    }
    #[Route("/card/reset", name: "reset")]
    public function resetDeck(
        Request $request,
        SessionInterface $session
    ): Response
    {
        $session->clear();

        return $this->redirectToRoute('deck');
    }

    #[Route("/card/deck/draw/{num<\d+>}", name: "draw_many")]
    public function drawMany(
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

        return $this->render('card/draw_many.html.twig', $data);
    }

    #[Route("/card/init", name: "card_init_get", methods: ['GET'])]
    public function init(): Response
    {
        return $this->render('card/init.html.twig');
    }

    #[Route("/card/deck/draw/{num<\d+>}", name: "card_init_post", methods: ['POST'])]
    public function initCallback(
        Request $request,
        SessionInterface $session
    ): Response {
        $numCard = $request->request->get('num_cards');

        $deck = new DeckOfCards();
        for ($i = 1; $i <= $numCard; $i++) {
            $deck->add(new CardGraphic());
        }
        $deck->drawCard();

        $session->set("deck_of_cards", $deck);
        $session->set("card_cards", $numCard);
        $session->set("card_drawn", 0);
        $session->set("card_left", 0);

        return $this->redirectToRoute('draw_many');
    }


    #[Route("/api", name: "api")]
    public function api(): Response
    {
        return $this->render('card/api.html.twig');
    }
}
