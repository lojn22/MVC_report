<?php

namespace App\Card;

class DeckOfCards
{
    protected array $deck = [];

    public function __construct()
    {
        // $cards = [];
        // foreach ($this->deck as $stringCard) {
        //     $card = new Card($stringCard[1], $stringCard[0]);
        //     array_push($this->deck, $card);
        // }
        // $this->deck = [];
        $suits = ['♥️', '♦️', '♠️', '♣️'];
        $ranks = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

        foreach ($suits as $suit) {
            foreach ($ranks as $value) {
                // $this->deck[] = new CardGraphic($suit, $value);
                $card = new CardGraphic($suit, $value);
                $this->deck[] = $card;

                // Debugging: Skriv ut suit och value
                // echo "Card: " . $card->getAsString() . " - Suit: " . $suit . " - Value: " . $value . "<br>";
                // Debugging: Skriv ut kortets representation
                // echo "Card2: " . $card->getAsString() .
                // " - Suit: " . $card->getSuit() . " - Value: " . $card->getValue() . "<br>";
            }
        }
    }

    public function getDeck(): array
    {
        return $this->deck;
    }

    public function deckShuffle(): void
    {
        shuffle($this->deck);
    }

    public function drawCard()
    {
        // if (count($this->deck) > 0);
        // $card = $this->deck[0];
        // array_splice($this->deck, 0, 1);

        // return $card;
        if (count($this->deck) > 0) {
            // return array_shift($this->deck);
            $card = array_shift($this->deck);

            // echo "Drar kort: " . $card->getAsString() . "\n"; // Debugging: Skriv ut vilket kort som dras
            return $card;
        }

        return null;
    }

    public function drawManyCards(int $num): array
    {
        // $cards = [];
        // if (count($this->deck) > $num);
        // for ($i=0; $i < $num ; $i++) {
        //     $cards[] = $this->deck[$i];
        // }
        // array_splice($this->deck, 0, $num);

        // return $cards;
        $cards = [];
        for ($i = 0; $i < $num && count($this->deck) > 0; ++$i) {
            $cards[] = array_shift($this->deck);
        }

        return $cards;
    }

    // public function drawRandom()
    // {
    //     if (count($this->deck) > 0) {
    //         $randomIndex = array_rand($this->deck);
    //         $randomCard = $this->deck[$randomIndex];
    //         array_splice($this->deck, $randomIndex, 1);
    //         return $randomCard;
    //     }
    //     return null;
    // }
}
