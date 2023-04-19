<?php

namespace App\Card;

class DeckOfCards
{
    public function __construct()
    {
        $this->value = null;
    }

    public function deck()
    {
        $colors = ['♥', '♦', '♠', '♣'];
        $values = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

        $deck = [];
        foreach ($colors as $color) {
            foreach ($values as $value) {
                $deck[] = $value . '' . $color;
            }
        }
        $this->$deck;
    }

    public function getDeck()
    {
        foreach ($deck as $card) {
            return $this->$card;
            }
    }
}





