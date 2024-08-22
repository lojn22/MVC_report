<?php

namespace App\Card;

class Card
{
    protected $value;
    protected $suit;

    public function __construct($value, $suit)
    {
        $this->value = $value;
        $this->suit = $suit;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getSuit()
    {
        return $this->suit;
    }

    public function getValueAsNumber(): int
    {
        switch ($this->value) {
            case 'J':
                return 11;
            case 'Q':
                return 12;
            case 'K':
                return 13;
            case 'A':
                return 1;
            default:
                return (int) $this->value;
        }
    }
}
