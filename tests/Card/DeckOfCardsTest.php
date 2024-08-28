<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;
use App\Card\CardGraphic;
use App\Card\DeckOfCards;

/**
 * Test cases for class.
 */
class DeckOfCardsTest extends TestCase
{
    /**
     * Test that the deck is initialized with 52 cards.
     */
    public function testDeckInitialization()
    {
        $deck = new DeckOfCards();
        $this->assertCount(52, $deck->getDeck());
    }

    /**
     * Test that the deck can be shuffled and still contains 52 cards.
     */
    public function testDeckShuffle()
    {
        $deck = new DeckOfCards();
        $deckBeforeShuffle = $deck->getDeck();
        $deck->deckShuffle();
        $deckAfterShuffle = $deck->getDeck();

        $this->assertCount(52, $deckAfterShuffle);
        $this->assertNotEquals($deckBeforeShuffle, $deckAfterShuffle, 'The deck order should be different after shuffling');
    }

    /**
     * Test that drawing a card reduces the deck size by one and returns a Card object.
     */
    public function testDrawCard()
    {
        $deck = new DeckOfCards();
        $initialCount = count($deck->getDeck());
        $card = $deck->drawCard();

        $this->assertInstanceOf(CardGraphic::class, $card);
        $this->assertCount($initialCount - 1, $deck->getDeck());
    }

    /**
     * Test that drawing multiple cards reduces the deck size correctly and returns an array of Card objects.
     */
    public function testDrawManyCards()
    {
        $deck = new DeckOfCards();
        $cardsToDraw = 5;
        $initialCount = count($deck->getDeck());
        $drawnCards = $deck->drawManyCards($cardsToDraw);

        $this->assertCount($cardsToDraw, $drawnCards);
        $this->assertCount($initialCount - $cardsToDraw, $deck->getDeck());

        foreach ($drawnCards as $card) {
            $this->assertInstanceOf(CardGraphic::class, $card);
        }
    }

    /**
     * Test that drawing more cards than are in the deck returns the correct number of cards and leaves the deck empty.
     */
    public function testDrawMoreCardsThanInDeck()
    {
        $deck = new DeckOfCards();
        $totalCards = count($deck->getDeck());
        $drawnCards = $deck->drawManyCards($totalCards + 10); // Try to draw more cards than exist

        $this->assertCount($totalCards, $drawnCards);
        $this->assertCount(0, $deck->getDeck());
    }
}
