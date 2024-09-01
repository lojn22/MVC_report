<?php

namespace App\Game;

use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class.
 */
class GameTest extends TestCase
{
    /**
     * Test that player draws a card and that the winner is correctly set when player reaches 21 or busts.
     */
    public function testPlayerDrawCard()
    {
        $deck = $this->createMock(DeckOfCards::class);
        $deck->method('drawCard')->willReturnOnConsecutiveCalls(
            new CardGraphic('♠️', '10'),
            new CardGraphic('♦️', 'J') // Total 21
        );

        $game = new Game($deck);
        $game->playerDrawCard(); // 10
        $game->playerDrawCard(); // J -> Total 21

        $this->assertEquals('Player', $game->getWinner());
    }

    /**
     * Test that player stays, bank plays, and winner is determined correctly.
     */
    public function testPlayerStays()
    {
        $deck = $this->createMock(DeckOfCards::class);
        $deck->method('drawCard')->willReturnOnConsecutiveCalls(
            new CardGraphic('♠️', '10'), // Player's cards
            new CardGraphic('♦️', '7'),
            new CardGraphic('♣️', '8'), // Bank's cards
            new CardGraphic('♠️', '9')
        );

        $game = new Game($deck);
        $game->playerDrawCard(); // 10
        $game->playerDrawCard(); // 7
        $game->playerStays();    // Bank plays: 8, 9 -> Total 17

        $this->assertEquals('Banken', $game->getWinner());
    }

    /**
     * Test determineWinner method for various scenarios.
     */
    public function testDetermineWinner()
    {
        // Skapa ett mockat kortdäck som returnerar korten i den ordning vi vill
        $deck = $this->createMock(DeckOfCards::class);
        $deck->method('drawCard')->willReturnOnConsecutiveCalls(
            new CardGraphic('♠️', '10'), // Player draws 10
            new CardGraphic('♠️', '10'), // Bank draws 10
            new CardGraphic('♠️', 'J'),  // Player draws A (Total 21)
            new CardGraphic('♠️', '7')   // Bank draws 7 (Total 17)
        );

        $game = new Game($deck);

        // Spelaren drar två kort
        $game->playerDrawCard();
        $game->playerDrawCard(); // Player reaches 21

        // Kontrollera att vinnaren inte har bestämts än
        $this->assertNull($game->getWinner());

        // Banken spelar sin tur
        $game->playerStays();

        // Kontrollera vem som vinner
        $this->assertEquals('Player', $game->getWinner());
    }

    public function testPlayerBusted()
    {
        // Spelaren bustar (går över 21)
        $deck = $this->createMock(DeckOfCards::class);
        $deck->method('drawCard')->willReturnOnConsecutiveCalls(
            new CardGraphic('♠️', '10'), // Player draws 10
            new CardGraphic('♠️', '9'),  // Player draws 9
            new CardGraphic('♠️', '5')   // Player draws 5 (Total 24)
        );

        $game = new Game($deck);

        // Spelaren drar tre kort och bustar
        $game->playerDrawCard();
        $game->playerDrawCard();
        $game->playerDrawCard();

        $this->assertEquals('Banken', $game->getWinner());
    }

    public function testBankBusted()
    {
        // Skapa ett mockat kortdäck med förbestämda kort som leder till att banken blir busted
        $deck = $this->createMock(DeckOfCards::class);
        $deck->method('drawCard')->willReturnOnConsecutiveCalls(
            new CardGraphic('♠️', '10'),  // Player draws 10
            new CardGraphic('♠️', '10'),  // Bank draws 10
            new CardGraphic('♠️', '5'),   // Player draws 5 (Total 15)
            new CardGraphic('♠️', 'K'),   // Bank draws K (Total 20)
            new CardGraphic('♠️', '6')    // Bank draws 6 (Total 26 -> Busted)
        );

        // Initiera spelet med mockat kortdäck
        $game = new Game($deck);

        // Simulera spelarens tur (här drar spelaren två kort)
        $game->playerDrawCard();  // Player gets 10
        $game->playerDrawCard();  // Player gets 5

        // Spelaren stannar, vilket betyder att banken ska spela sin tur
        $game->playerStays();  // Bank draws 10, K, and 6 (which busts)

        // Kontrollera att vinnaren är spelaren, eftersom banken blev busted
        $this->assertEquals('Player', $game->getWinner());
    }

    /**
     * Test that the correct winner is returned.
     */
    public function testGetWinner()
    {
        $deck = $this->createMock(DeckOfCards::class);
        $game = new Game($deck);

        $game->setWinner('Player');
        $this->assertEquals('Player', $game->getWinner());
    }

    /**
     * Test that the bank plays its turn and the winner is determined correctly.
     */
    public function testBankPlay()
    {
        // Skapa ett mockat kortdäck med förbestämda kort
        $deck = $this->createMock(DeckOfCards::class);
        $deck->method('drawCard')->willReturnOnConsecutiveCalls(
            new CardGraphic('♠️', '9'), // Bankens första kort
            new CardGraphic('♦️', '7'), // Spelarens första kort
            new CardGraphic('♠️', '5'), // Bankens andra kort (total 14)
            new CardGraphic('♥️', 'K')  // Spelarens andra kort (total 17)
        );

        // Skapa en ny spelinstans med det mockade kortdäcket
        $game = new Game($deck);

        // Simulera spelarens tur
        $game->playerDrawCard();  // Spelaren drar 7
        $game->playerDrawCard();  // Spelaren drar K -> Total 17

        // Banken spelar sin tur
        $game->bankPlay();        // Banken drar 9, 5 -> Total 18, bör dra igen.

        // Hämta och verifiera bankens hand
        $bankHand = $game->getBankHand();
        $this->assertInstanceOf(CardHand::class, $bankHand, 'Bankens hand ska vara av typen CardHand.');
        $this->assertCount(2, $bankHand->getCards(), 'Bankens hand ska ha 2 kort.');
        $this->assertEquals(18, $bankHand->getSum(), 'Bankens handvärde ska vara 18 innan eventuell omdragning.');

        // Kontrollera handvärdena
        $playerHandValue = $game->getPlayer()->getHandValue();
        $bankHandValue = $game->getBank()->getHandValue();

        // Bestäm förväntad vinnare baserat på handvärdena
        $expectedWinner = ($playerHandValue > $bankHandValue && $playerHandValue <= 21) ? 'Player' : 'Banken';

        // Verifiera vinnaren
        $this->assertEquals($expectedWinner, $game->getWinner(), 'Den förväntade vinnaren ska vara korrekt.');
    }

    /**
     * Test that getDeck() returns the correct DeckOfCards instance.
     */
    public function testGetDeck()
    {
        // Skapa ett mockat kortdäck
        $mockDeck = $this->createMock(DeckOfCards::class);

        // Skapa en ny instans av spelet med det mockade kortdäcket
        $game = new Game($mockDeck);

        // Anropa getDeck() och kontrollera att det returnerar rätt objekt
        $returnedDeck = $game->getDeck();
        $this->assertSame($mockDeck, $returnedDeck, 'getDeck() ska returnera det kortdäck som skickades in i konstruktorn.');
    }
}
