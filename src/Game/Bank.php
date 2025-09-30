<?php

namespace App\Game;

use App\Card\CardHand;
use App\Card\DeckOfCards;

class Bank
{
    private CardHand $hand;

    public function __construct()
    {
        $this->hand = new CardHand();
    }

    public function drawCard(DeckOfCards $deck): void
    {
        // $this->hand->addCard($deck->drawCard());
        $card = $deck->drawCard();
        if ($card !== null) {
            $this->hand->addCard($card);
        }
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
        while ($this->getHandValue() < 17) {
            $this->drawCard($deck);
        }
    }

    public function resetHand(): void
    {
        $this->hand->resetHand();
    }
}
