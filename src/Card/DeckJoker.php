<?php

namespace App\Card;

class DeckJoker extends DeckOfCards
{
    protected $jokers = [
        'joker1',
        'joker2',
        'joker3',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->deck = parent::getDeck();
        $this->deck = array_merge($this->deckArray, $this->jokers);
    }

    public function getDeck(): array
    {
        return $this->deck;
    }
}