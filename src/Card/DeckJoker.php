<?php

namespace App\Card;

class DeckJoker extends DeckOfCards
{
    protected $joker = [
        'joker1',
        'joker2',
        'joker3',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->deck = parent::getDeck();
        $this->deck = array_merge($this->deck, $this->joker);
    }

    public function getDeck(): array
    {
        return $this->deck;
    }
}
