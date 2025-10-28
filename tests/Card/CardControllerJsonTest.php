<?php

namespace App\Tests\Controller;

use App\Controller\CardControllerJson;
use App\Card\DeckOfCards;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class CardControllerJsonTest extends TestCase
{
    private $controller;
    private $session;
    private $deck;

    protected function setUp(): void
    {
        $this->controller = new CardControllerJson();
        $this->session = $this->createMock(SessionInterface::class);
        $this->deck = $this->createMock(DeckOfCards::class);
    }

    public function testJsonDeck_CreatesNewDeckIfNotInSession()
    {
        $this->session->method('get')->willReturn(null);
        $this->session->expects($this->once())->method('set');

        $response = $this->controller->jsonDeck($this->session);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertStringContainsString('Kortlek', $response->getContent());
    }

    public function testJsonShuffle_ReturnsShuffledDeck()
    {
        $this->deck->expects($this->once())->method('deckshuffle');
        $this->deck->method('getDeck')->willReturn(['A♥️', 'K♦️']);

        $this->session->method('get')->willReturn($this->deck);
        $this->session->expects($this->once())->method('set');

        $response = $this->controller->jsonShuffle($this->session);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertStringContainsString('deck', $response->getContent());
    }

    public function testJsonDraw_ReturnsCardAndCount()
    {
        $this->deck->method('drawCard')->willReturn('A♥️');
        $this->deck->method('getDeck')->willReturn(['K♦️', 'Q♣️']);

        $this->session->method('get')->willReturn($this->deck);

        $response = $this->controller->jsonDraw($this->session);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertStringContainsString('card_left', $response->getContent());
    }

    public function testJsonDrawMany_ThrowsExceptionWhenTooMany()
    {
        $this->expectException(\Exception::class);
        $this->controller->jsonDrawMany(60, $this->session);
    }
}
