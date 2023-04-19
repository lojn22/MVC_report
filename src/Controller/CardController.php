<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\DiceGraphic;
use App\Card\DiceHand;
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
    public function deck(): Response
    {
        $deck = new DeckOfCards();
        $data = ["test" =>$deck->deck()];
        return $this->render('card/card.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "shuffle")]
    public function shuffle(): Response
    {
        return $this->render('card/home.html.twig');
    }

    #[Route("/card/deck/draw", name: "draw")]
    public function draw(): Response
    {
        return $this->render('card/home.html.twig');
    }

    #[Route("/card/deck/draw/{num<\d+>}", name: "number")]
    public function number(int $num): Response
    {
        return $this->render('card/home.html.twig');
    }
    
    #[Route("/api", name: "api")]
    public function api(): Response
    {
        return $this->render('card/api.html.twig');
    }

    // #[Route("/card/deck/deal/{players<\d+>}/{cards<\d+>}", name: "play")]
    // public function play(int $player, int $card): Response
    // {
    //     return $this->render('card/home.html.twig');
    // }
}