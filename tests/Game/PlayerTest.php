<?php

namespace App\Game;

use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class.
 */
class PlayerTest extends TestCase
{
    /**
     * Test that the player draws a card and adds it to their hand.
     */
    public function testDrawCard()
    {
        $deck = $this->createMock(DeckOfCards::class);
        $deck->method('drawCard')->willReturn(new CardGraphic('♠️', 'Q'));

        $player = new Player();
        $player->drawCard($deck);

        $this->assertEquals(1, $player->getHand()->getNumberCards());
        $this->assertEquals(['♠️Q'], $player->getHand()->getString());
    }

    /**
     * Test that the hand is returned correctly.
     */
    public function testGetHand()
    {
        $player = new Player();

        $this->assertInstanceOf(CardHand::class, $player->getHand());
    }

    /**
     * Test that the hand value is calculated correctly.
     */
    public function testGetHandValue()
    {
        $deck = $this->createMock(DeckOfCards::class);
        $deck->method('drawCard')->willReturnOnConsecutiveCalls(
            new CardGraphic('♠️', 'Q'),
            new CardGraphic('♦️', '7')
        );

        $player = new Player();
        $player->drawCard($deck);  // Q = 12
        $player->drawCard($deck);  // 7 = 7

        $this->assertEquals(19, $player->getHandValue());
    }

    /**
     * Test that the player is busted when the hand value exceeds 21.
     */
    public function testIsBusted()
    {
        $deck = $this->createMock(DeckOfCards::class);
        $deck->method('drawCard')->willReturnOnConsecutiveCalls(
            new CardGraphic('♠️', 'Q'), // 12
            new CardGraphic('♦️', '7'), // 7
            new CardGraphic('♣️', '5')  // 5
        );

        $player = new Player();
        $player->drawCard($deck);
        $player->drawCard($deck);
        $player->drawCard($deck);

        $this->assertTrue($player->isBusted());
    }

    /**
     * Test that the hand is reset correctly.
     */
    public function testResetHand()
    {
        $deck = $this->createMock(DeckOfCards::class);
        $deck->method('drawCard')->willReturn(new CardGraphic('♠️', 'Q'));

        $player = new Player();
        $player->drawCard($deck);
        $this->assertEquals(1, $player->getHand()->getNumberCards());

        $player->resetHand();
        $this->assertEquals(0, $player->getHand()->getNumberCards());
    }
}
