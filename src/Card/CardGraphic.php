<?php

namespace App\Card;

class CardGraphic extends Card
{
    private $representation = [
        "♥️A","♥️2","♥️3","♥️4","♥️5","♥️6","♥️7","♥️8","♥️9","♥️10","♥️J","♥️Q","♥️K",
        "♦️A","♦️2","♦️3","♦️4","♦️5","♦️6","♦️7","♦️8","♦️9","♦️10","♦️J","♦️Q","♦️K",
        "♠️A","♠️2","♠️3","♠️4","♠️5","♠️6","♠️7","♠️8","♠️9","♠️10","♠️J","♠️Q","♠️K",
        "♣️A","♣️2","♣️3","♣️4","♣️5","♣️6","♣️7","♣️8","♣️9","♣️10","♣️J","♣️Q","♣️K",
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function getAsString(): string
    {
        return $this->representation[$this->value - 1];
    }
}
