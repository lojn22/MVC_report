<?php

namespace App\Game;

use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for Bank class.
 */
class BankTest extends TestCase
{
    /**
     * Test that the bank draws a card and adds it to its hand.
     */
    public function testDrawCard()
    {
        $deck = $this->createMock(DeckOfCards::class);
        $deck->method('drawCard')->willReturn(new CardGraphic('♥️', 'K'));

        $bank = new Bank($deck);
        $bank->drawCard($deck);

        $this->assertEquals(1, $bank->getHand()->getNumberCards());
        $this->assertEquals(['♥️K'], $bank->getHand()->getString());
    }

    /**
     * Test that the hand is returned correctly.
     */
    public function testGetHand()
    {
        $deck = $this->createMock(DeckOfCards::class);
        $bank = new Bank($deck);

        $this->assertInstanceOf(CardHand::class, $bank->getHand());
    }

    /**
     * Test that the hand value is calculated correctly.
     */
    public function testGetHandValue()
    {
        $deck = $this->createMock(DeckOfCards::class);
        $deck->method('drawCard')->willReturnOnConsecutiveCalls(
            new CardGraphic('♥️', 'K'),
            new CardGraphic('♦️', '5')
        );

        $bank = new Bank($deck);
        $bank->drawCard($deck);  // K = 13
        $bank->drawCard($deck);  // 5 = 5

        $this->assertEquals(18, $bank->getHandValue());
    }

    /**
     * Test that the bank plays its turn correctly.
     */
    public function testPlayTurn()
    {
        $deck = $this->createMock(DeckOfCards::class);
        // Mockar tre kort som dras med låga värden för att simulera att banken måste dra flera kort
        $deck->method('drawCard')->willReturnOnConsecutiveCalls(
            new CardGraphic('♠️', '5'),  // 5
            new CardGraphic('♦️', '6'),  // 6
            new CardGraphic('♣️', '6')   // 6
        );

        $bank = new Bank($deck);
        $bank->playTurn($deck);

        // Eftersom banken måste fortsätta att dra tills summan är 17 eller mer, bör tre kort dras
        $this->assertEquals(3, $bank->getHand()->getNumberCards());
        $this->assertEquals(17, $bank->getHandValue());  // Summan av 5+6+6 = 17
    }

    /**
     * Test that the hand is reset correctly.
     */
    public function testResetHand()
    {
        $deck = $this->createMock(DeckOfCards::class);
        $deck->method('drawCard')->willReturn(new CardGraphic('♥️', 'K'));

        $bank = new Bank($deck);
        $bank->drawCard($deck);
        $this->assertEquals(1, $bank->getHand()->getNumberCards());

        $bank->resetHand();
        $this->assertEquals(0, $bank->getHand()->getNumberCards());
    }
}
