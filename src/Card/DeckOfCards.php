<?php

namespace App\Card;

class DeckOfCards
{
    protected array $deckArray = [
    '♥️A','♥️2','♥️3','♥️4','♥️5','♥️6','♥️7','♥️8','♥️9','♥️10','♥️J','♥️Q','♥️K',
    '♦️A','♦️2','♦️3','♦️4','♦️5','♦️6','♦️7','♦️8','♦️9','♦️10','♦️J','♦️Q','♦️K',
    '♠️A','♠️2','♠️3','♠️4','♠️5','♠️6','♠️7','♠️8','♠️9','♠️10','♠️J','♠️Q','♠️K',
    '♣️A','♣️2','♣️3','♣️4','♣️5','♣️6','♣️7','♣️8','♣️9','♣️10','♣️J','♣️Q','♣️K',
    ];
    // protected $drawnCard;

    public function __construct()
    {
        $this->deck = [];
        foreach ($this->deckArray as $stringCard) {
            $card = new Card($stringCard[1], $stringCard[0]);
            array_push($this->deck, $card);
        }
    }

    public function getDeck(): array
    {
        return $this->deckArray;
    }

    public function deckShuffle()
    {
        shuffle($this->deckArray);
    }

    public function drawCard()
    {
        if (count($this->deckArray) > 0);
        $card = $this->deckArray[0];
        array_splice($this->deckArray, 0, 1);

        return $card;
    }

    public function drawManyCards(int $num)
    {
        $cards = [];
        if (count($this->deckArray) > $num);
        for ($i=0; $i < $num ; $i++) {
            $cards[] = $this->deckArray[$i];
        }
        array_splice($this->deckArray, 0, $num);

        return $cards;
    }
}

// public function drawRandom(int $amount=1)
// {
//     if (count($this->deck) > 0)
//     {
//         if ($amount ==1){

//             $randomCard[] = array_rand($this->deck, $amount);
//         }
//         else {

//             $randomCard = array_rand($this->deck, $amount);
//         }

//         return $this->deck[$randomCard[0]];
//     }
// }
