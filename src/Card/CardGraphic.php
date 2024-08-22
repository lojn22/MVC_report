<?php

namespace App\Card;

class CardGraphic extends Card implements \JsonSerializable
{
    private $representation = [
        '♥️A', '♥️2', '♥️3', '♥️4', '♥️5', '♥️6', '♥️7', '♥️8', '♥️9', '♥️10', '♥️J', '♥️Q', '♥️K',
        '♦️A', '♦️2', '♦️3', '♦️4', '♦️5', '♦️6', '♦️7', '♦️8', '♦️9', '♦️10', '♦️J', '♦️Q', '♦️K',
        '♠️A', '♠️2', '♠️3', '♠️4', '♠️5', '♠️6', '♠️7', '♠️8', '♠️9', '♠️10', '♠️J', '♠️Q', '♠️K',
        '♣️A', '♣️2', '♣️3', '♣️4', '♣️5', '♣️6', '♣️7', '♣️8', '♣️9', '♣️10', '♣️J', '♣️Q', '♣️K',
    ];

    public function __construct($suit, $value)
    {
        parent::__construct($value, $suit);
    }

    public function getAsString(): string
    {
        // Skapa strängen som man vill hitta
        $cardString = $this->getSuit() . $this->getValue();

        // Leta upp index i representation arrayen
        $index = array_search($cardString, $this->representation);

        // Om index hittas, returnera rätt representation
        if (false !== $index) {
            return $this->representation[$index];
        }

        // Om inte, returnera en standardtext för att indikera problem
        return $cardString;
    }

    public function __toString(): string
    {
        return $this->getAsString();
    }

    public function jsonSerialize(): mixed
    {
        return $this->getAsString();
    }
}
