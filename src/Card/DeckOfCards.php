<?php

namespace App\Card;

class DeckOfCards
{
    protected $deck;
    protected $drawnCard;

    public function __construct()
    {
        $this->deck = [
        '♥️A','♥️2','♥️3','♥️4','♥️5','♥️6','♥️7','♥️8','♥️9','♥️10','♥️J','♥️Q','♥️K',
        '♦️A','♦️2','♦️3','♦️4','♦️5','♦️6','♦️7','♦️8','♦️9','♦️10','♦️J','♦️Q','♦️K',
        '♠️A','♠️2','♠️3','♠️4','♠️5','♠️6','♠️7','♠️8','♠️9','♠️10','♠️J','♠️Q','♠️K',
        '♣️A','♣️2','♣️3','♣️4','♣️5','♣️6','♣️7','♣️8','♣️9','♣️10','♣️J','♣️Q','♣️K',
        ];

        $this->drawnCard = [];
    }

    public function getDeck(): array
    {
        return $this->deck;
    }
    
    public function deckShuffle()
    {
        shuffle($this->deck);
    }

    public function drawRandom()
    {
        if (count($this->deck) > 0)
        {
            $randomCard = array_rand($this->deck, 1);
            $card = $this->deck[$randomCard];
            array_push($this->drawnCard, $card);
            return $card;
        }
        
    }
    public function leftOfDeck()
    {
        $index = array_search($card, $this->deck);
        if (index !==false)
        {
            unset($this->deck[index]);
            return $this->deck;
        }
    }

    public function getDrawnCard()
    {
        return $this->drawnCard;
    }
}
