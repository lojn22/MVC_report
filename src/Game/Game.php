<?php

namespace App\Game;

use App\Card\CardHand;
use App\Card\DeckOfCards;

/**
 * The Game class represents a game of Blackjack where a player competes against the bank.
 * It manages the game flow, including drawing cards, determining the winner, and tracking
 * the current state of the game.
 */
class Game
{
    /**
     * @var Player the player participating in the game
     */
    private Player $player;

    /**
     * @var Bank the bank (dealer) participating in the game
     */
    private Bank $bank;

    /**
     * @var DeckOfCards the deck of cards used in the game
     */
    private DeckOfCards $deck;

    /**
     * @var string|null the winner of the game, either 'Player', 'Banken', or null if the game is ongoing
     */
    private ?string $winner = null;

    /**
     * Constructs a new Game instance.
     *
     * @param DeckOfCards $deck the deck of cards used in the game
     */
    public function __construct(DeckOfCards $deck)
    {
        $this->player = new Player();
        $this->bank = new Bank();
        $this->deck = $deck;
    }

    /**
     * Allows the player to draw a card from the deck.
     * If the player's hand value equals 21, the player wins.
     * If the player's hand value exceeds 21, the bank wins.
     */
    public function playerDrawCard(): void
    {
        $this->player->drawCard($this->deck);

        if (21 === $this->player->getHandValue()) {
            $this->winner = 'Player';
        } elseif ($this->player->isBusted()) {
            $this->winner = 'Banken';
        }
    }

    /**
     * The player decides to stay, meaning they won't draw any more cards.
     * The bank then plays its turn and the winner is determined.
     */
    public function playerStays(): void
    {
        $this->bank->playTurn($this->deck);
        $this->determineWinner();
    }

    /**
     * Manually sets the winner of the game.
     *
     * @param string $winner the winner of the game, either 'Player' or 'Banken'
     */
    public function setWinner(string $winner): void
    {
        $this->winner = $winner;
    }

    /**
     * The bank plays its turn, drawing cards according to its strategy.
     * After the bank's turn, the winner is determined.
     */
    public function bankPlay(): void
    {
        $this->bank->playTurn($this->deck);
        $this->determineWinner();
    }

    /**
     * Returns the bank's hand of cards.
     *
     * @return CardHand the bank's current hand of cards
     */
    public function getBankHand(): CardHand
    {
        return $this->bank->getHand();
    }

    /**
     * Determines the winner of the game based on the values of the player and bank's hands.
     */
    public function determineWinner(): void
    {
        $playerValue = $this->player->getHandValue();
        $bankValue = $this->bank->getHandValue();

        if ($this->player->isBusted()) {
            $this->winner = 'Banken'; // Player has over 21, bank wins
        } elseif ($bankValue > 21) {
            $this->winner = 'Player'; // Bank has over 21, player wins
        } elseif (21 === $playerValue && 21 !== $bankValue) {
            $this->winner = 'Player'; // Player has 21 and bank does not
        } elseif (21 === $bankValue && 21 !== $playerValue) {
            $this->winner = 'Banken'; // Bank has 21 and player does not
        } elseif ($playerValue > $bankValue && $playerValue <= 21) {
            $this->winner = 'Player'; // Player has a higher value than the bank
        } elseif ($playerValue < $bankValue && $bankValue <= 21) {
            $this->winner = 'Banken'; // Bank has a higher value than the player
        } else {
            $this->winner = 'Banken'; // Tie, bank wins
        }
    }

    /**
     * Returns the winner of the game.
     *
     * @return string|null the winner of the game, either 'Player', 'Banken', or null if the game is ongoing
     */
    public function getWinner(): ?string
    {
        return $this->winner;
    }

    /**
     * Returns the player participating in the game.
     *
     * @return Player the player in the game
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * Returns the bank participating in the game.
     *
     * @return Bank the bank in the game
     */
    public function getBank(): Bank
    {
        return $this->bank;
    }

    /**
     * Returns the deck of cards used in the game.
     *
     * @return DeckOfCards the deck of cards used in the game
     */
    public function getDeck(): DeckOfCards
    {
        return $this->deck;
    }
}
