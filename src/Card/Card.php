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
        // Map face card values to integers
        switch ($this->value) {
            case 'J':
                return 11;
            case 'Q':
                return 12;
            case 'K':
                return 13;
            case 'A':
                // Return a special case or handle Ace value separately
                return 1; // default to 1, handle Ace separately in game logic
            default:
                return (int)$this->value; // Convert numeric value to integer
        }
    }
}

// $this->deck = [
//     'h1','h2','h3','h4','h5','h6','h7','h8','h9','h10','h11','h12','h13',
//     'd1','d2','d3','d4','d5','d6','d7','d8','d9','d10','d11','d12','d13',
//     's1','s2','s3','s4','s5','s6','s7','s8','s9','s10','s11','s12','s13',
//     'c1','c2','c3','c4','c5','c6','c7','c8','c9','c10','c11','c12','c13'
// ];
