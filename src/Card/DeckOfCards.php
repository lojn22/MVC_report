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
        $this->cardsLeft = [];
    }

    public function getDeck(): array
    {
        return $this->deck;
    }
    
    public function deckShuffle()
    {
        shuffle($this->deck);
    }

    public function drawRandom(int $amount=1)
    {
        if (count($this->deck) > 0)
        {
            if ($amount ==1){

                $randomCard[] = array_rand($this->deck, $amount);
            }
            else {

                $randomCard = array_rand($this->deck, $amount);
            }

            // $card = $this->deck[$randomCard];
            // array_push($this->drawnCard, $card);
            return $this->deck[$randomCard[0]];
        }
    }
    public function leftOfDeck()
    {
        $randomCard = array_rand($this->deck, 1);
        $card = $this->deck[$randomCard];
        $index = array_search($card, $this->deck);
        $count =0;
        while ($index !==false)
        {
            unset($this->deck[$index]);
            $count++;
            $index =array_search($card, $this->deck);
        }
        return array($count, $card);
    }

    public function getDrawnCard()
    {
        return $this->drawnCard;
    }
}
