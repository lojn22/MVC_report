<?php

namespace App\Card;

use App\Card\Card;

class CardHand
{
    private $hand = [];

    public function add(DeckOfCards $deck): void
    {
        $this->hand[] = $deck;
    }

    public function draw()
    {
        $card = $this->hand[0]->drawRandom();
        var_dump($card);
        // foreach ($this->hand as $card) {
        //     $card->draw();
        // }
        return $card;

    }

    public function getNumberCards(): int
    {
        return count($this->hand);
    }

    public function getValues(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getValue();
        }
        return $values;
    }

    public function getString(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getAsString();
        }
        return $values;
    }
}

// public function drawRandom()
    // {
    //     if (count($this->deck) > 0)
    //     {
    //         $randomCard = array_rand($this->deck, 1);
    //         $card = $this->deck[$randomCard];
    //         array_push($this->drawnCard, $card);
    //         return $card;
    //     }
        
    // }
    // public function leftOfDeck()
    // {
    //     $index = array_search($card, $this->deck);
    //     if (index !==false)
    //     {
    //         unset($this->deck[index]);
    //         return $this->deck;
    //     }
    // }

    // public function getDrawnCard()
    // {
    //     return $this->drawnCard;
    // }