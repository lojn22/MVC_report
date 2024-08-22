<?php

namespace App\Game;

use App\Card\CardHand;
use App\Card\DeckOfCards;

class Game
{
    private Player $player;
    private Bank $bank;
    private DeckOfCards $deck;
    private ?string $winner = null;

    public function __construct(DeckOfCards $deck)
    {
        $this->player = new Player();
        $this->bank = new Bank($deck);
        $this->deck = $deck;
    }

    public function playerDrawCard(): void
    {
        $this->player->drawCard($this->deck);

        if (21 === $this->player->getHandValue()) {
            $this->winner = 'Player';
        } elseif ($this->player->isBusted()) {
            $this->winner = 'Banken';
        }
    }

    public function playerStays(): void
    {
        $this->bank->playTurn($this->deck);

        // Determine the winner
        $this->determineWinner();
    }

    public function setWinner(string $winner): void
    {
        $this->winner = $winner;
    }

    public function bankPlay(): void
    {
        // Låt banken spela sin tur, kanske genom att dra kort tills en viss summa nås
        $this->bank->playTurn($this->deck);

        // Efter att banken har spelat kan du eventuellt avgöra om någon har vunnit
        $this->determineWinner();
    }

    public function getBankHand(): CardHand
    {
        return $this->bank->getHand();
    }

    public function determineWinner(): void
    {
        $playerValue = $this->player->getHandValue();
        $bankValue = $this->bank->getHandValue();

        if ($this->player->isBusted()) {
            $this->winner = 'Banken'; // Spelaren har över 21, banken vinner
        } elseif ($bankValue > 21) {
            $this->winner = 'Player'; // Banken har över 21, spelaren vinner
        } elseif (21 === $playerValue && 21 !== $bankValue) {
            $this->winner = 'Player'; // Spelaren har 21 och banken har inte 21
        } elseif (21 === $bankValue && 21 !== $playerValue) {
            $this->winner = 'Banken'; // Banken har 21 och spelaren har inte 21
        } elseif ($playerValue > $bankValue && $playerValue <= 21) {
            $this->winner = 'Player'; // Spelaren har högre värde än banken
        } elseif ($playerValue < $bankValue && $bankValue <= 21) {
            $this->winner = 'Banken'; // Banken har högre värde än spelaren
        } else {
            $this->winner = 'Banken'; // Likadant värde, banken vinner
        }
    }

    public function getWinner(): ?string
    {
        return $this->winner;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getBank(): Bank
    {
        return $this->bank;
    }

    public function getDeck(): DeckOfCards
    {
        return $this->deck;
    }
}
