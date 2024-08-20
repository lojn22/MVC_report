<?php

namespace App\Game;

use App\Card\CardHand;
use App\Card\Card;
use App\Card\DeckOfCards;

class Bank
{
    private CardHand $hand;
    // private DeckOfCards $deck;

    public function __construct(DeckOfCards $deck)
    {
        $this->hand = new CardHand();
        // $this->deck = $deck;
    }

    public function drawCard(DeckOfCards $deck): void
    {
        $this->hand->addCard($deck->drawCard());
    }

    public function getHand(): CardHand
    {
        return $this->hand;
    }

    public function getHandValue(): int
    {
        return $this->hand->getSum();
    }

    public function playTurn(DeckOfCards $deck): void
    {
        // Bank draws cards until its hand value is at least 17
        while ($this->getHandValue() < 17) {
            $this->drawCard($deck);
        }
    }

    public function resetHand(): void
    {
        $this->hand->resetHand();
    }
}