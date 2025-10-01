<?php

namespace App\Card;

class DeckOfCards
{
    protected array $deck = [];

    public function __construct()
    {
        $suits = ['♥️', '♦️', '♠️', '♣️'];
        $ranks = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

        foreach ($suits as $suit) {
            foreach ($ranks as $value) {
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
        if (count($this->deck) > 0) {
            $card = array_shift($this->deck);

            // echo "Drar kort: " . $card->getAsString() . "\n"; // Debugging: Skriv ut vilket kort som dras
            return $card;
        }

        return null;
    }

    public function add(Card $card): void
    {
        $this->deck[] = $card;
    }

    public function drawManyCards(int $num): array
    {
        $cards = [];
        for ($i = 0; $i < $num && count($this->deck) > 0; ++$i) {
            $cards[] = array_shift($this->deck);
        }

        return $cards;
    }
}
