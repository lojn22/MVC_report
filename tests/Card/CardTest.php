<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for Card class.
 */
class CardTest extends TestCase
{
    /**
     * Test that the card's value and suit are correctly assigned and retrieved.
     */
    public function testGetValueAndSuit()
    {
        $card = new Card('K', 'hearts');
        $this->assertEquals('K', $card->getValue());
        $this->assertEquals('hearts', $card->getSuit());
    }

    /**
     * Test that face cards return the correct numerical values.
     *
     * J = 11, Q = 12, K = 13, A = 1.
     */
    public function testGetValueAsNumberForFaceCards()
    {
        $cardJack = new Card('J', 'spades');
        $cardQueen = new Card('Q', 'diamonds');
        $cardKing = new Card('K', 'clubs');
        $cardAce = new Card('A', 'hearts');

        $this->assertEquals(11, $cardJack->getValueAsNumber());
        $this->assertEquals(12, $cardQueen->getValueAsNumber());
        $this->assertEquals(13, $cardKing->getValueAsNumber());
        $this->assertEquals(1, $cardAce->getValueAsNumber());
    }

    /**
     * Test that numeric cards return the correct numerical values.
     */
    public function testGetValueAsNumberForNumericCards()
    {
        $cardTwo = new Card('2', 'hearts');
        $cardTen = new Card('10', 'clubs');

        $this->assertEquals(2, $cardTwo->getValueAsNumber());
        $this->assertEquals(10, $cardTen->getValueAsNumber());
    }
}
