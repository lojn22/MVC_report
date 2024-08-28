<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Card;
use App\Card\CardHand;

/**
 * Test cases for CarHand class.
 */
class CardHandTest extends TestCase
{
     /**
     * Test that a card can be added to the hand and that the hand size is correct.
     */
    public function testAddCardAndGetNumberCards()
    {
        $hand = new CardHand();
        $card = new Card('K', 'hearts');
        $hand->addCard($card);

        $this->assertEquals(1, $hand->getNumberCards());
    }

    /**
     * Test that the values of the cards in the hand are returned correctly.
     */
    public function testGetValues()
    {
        $hand = new CardHand();
        $hand->addCard(new Card('K', 'hearts'));
        $hand->addCard(new Card('5', 'diamonds'));

        $values = $hand->getValues();

        $this->assertEquals(['K', '5'], $values);
    }

    /**
     * Test that the sum of the card values is calculated correctly.
     */
    public function testGetSum()
    {
        $hand = new CardHand();
        $hand->addCard(new Card('K', 'hearts'));  // Value = 13
        $hand->addCard(new Card('5', 'diamonds')); // Value = 5
        $hand->addCard(new Card('A', 'spades'));   // Value = 1 (will not be adjusted to 14 because sum > 11)

        $sum = $hand->getSum();

        // The sum should be 13 + 5 + 1 = 19
        $this->assertEquals(19, $sum);
    }

    /**
     * Test that the hand can be reset and is empty afterwards.
     */
    public function testResetHand()
    {
        $hand = new CardHand();
        $hand->addCard(new Card('K', 'hearts'));
        $hand->resetHand();

        $this->assertEquals(0, $hand->getNumberCards());
        $this->assertEmpty($hand->getValues());
    }

    /**
     * Test that the string representations of the cards in the hand are returned correctly.
     */
    public function testGetString()
    {
        $hand = new CardHand();
        $card1 = new CardGraphic('♥️', 'K');
        $card2 = new CardGraphic('♦️', '5');

        $hand->addCard($card1);
        $hand->addCard($card2);

        $strings = $hand->getString();

        $this->assertEquals(['♥️K', '♦️5'], $strings);
    }
}
