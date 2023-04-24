<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
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
    public function deckCards(): Response
    {
        $deck = new DeckOfCards();

        $data = [
            "deck" => $deck->getDeck(),
        ];

        return $this->render('card/test/deck.html.twig', $data);
    }
    
    #[Route("/card/deck/shuffle", name: "shuffle")]
    public function shuffleDeck(): Response
    {
        $deck = new DeckOfCards();

        $data = [
            "shuffle" => $deck->deckshuffle(),
            "deck" => $deck->getDeck(),

        ];

        return $this->render('card/test/deckshuffle.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "draw")]
    public function cardDraw(): Response
    {
        $deck = new DeckOfCards();

        $data = [
            "draw" => $deck->drawRandom(),
            "deck" => $deck->getDrawnCard(),
            // "num_cards" => count($card),
        ];

        return $this->render('card/test/draw.html.twig', $data);
    }

    // #[Route("/card/deck/draw/{num<\d+>}", name: "draw_many")]
    // public function drawMany(int $num): Response
    // {
    

    //     $data = [
    //         "num_cards" => $hand->getNumberCards(),
    //         "cardDraw" => $hand->getString(),
    //     ];

    //     return $this->render('card/test/draw_many.html.twig', $data);
    // }

    #[Route("/card/init", name: "card_init_get", methods: ['GET'])]
    public function init(): Response
    {
        return $this->render('card/init.html.twig');
    }

    #[Route("/card/init", name: "card_init_post", methods: ['POST'])]
    public function initCallback(
        Request $request,
        SessionInterface $session
    ): Response {
        $numCard = $request->request->get('num_cards');

        $hand = new CardHand();
        for ($i = 1; $i <= $numCard; $i++) {
            $hand->add(new CardGraphic());
        }
        $hand->draw();

        $session->set("card_cardhand", $hand);
        $session->set("card_cards", $numCard);
        $session->set("card_round", 0);
        $session->set("card_total", 0);

        return $this->redirectToRoute('card_play');
    }

    #[Route("/card/play", name: "card_play", methods: ['GET'])]
    public function play(
        SessionInterface $session
    ): Response {
        $cardhand = $session->get("card_cardhand");

        $data = [
            "cardCards" => $session->get("card_cards"),
            "cardRound" => $session->get("card_round"),
            "cardTotal" => $session->get("card_total"),
            "cardValues" => $cardhand->getString()
        ];

        return $this->render('card/play.html.twig', $data);
    }

    #[Route("/card/draw", name: "card_draw", methods: ['POST'])]
    public function drawCard(
        SessionInterface $session
    ): Response {
        $hand = $session->get("card_cardhand");
        $hand->draw();

        $roundTotal = $session->get("card_round");
        $round = 0;
        $values = $hand->getValues();
        foreach ($values as $value) {
            if ($value === 1) {
                $round = 0;
                $roundTotal = 0;
                $this->addFlash(
                    'warning',
                    'You got a 1 and you lost the round points!'
                );
                break;
            }
            $round += $value;
        }

        $session->set("card_round", $roundTotal + $round);

        return $this->redirectToRoute('card_play');
    }

    #[Route("/card/save", name: "card_save", methods: ['POST'])]
    public function save(
        SessionInterface $session
    ): Response {
        $roundTotal = $session->get("card_round");
        $gameTotal = $session->get("card_total");

        $session->set("card_round", 0);
        $session->set("card_total", $roundTotal + $gameTotal);

        $this->addFlash(
            'notice',
            'Your round was saved to the total!'
        );

        return $this->redirectToRoute('card_play');
    }
}
