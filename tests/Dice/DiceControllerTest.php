<?php

namespace App\Tests\Controller;

use App\Controller\DiceGameController;
use App\Dice\DiceHand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class DiceGameControllerTest extends TestCase
{
    private DiceGameController $controller;
    private $session;

    protected function setUp(): void
    {
        $this->session = $this->createMock(SessionInterface::class);

        // Mock controller och override render, redirectToRoute och addFlash
        $this->controller = $this->getMockBuilder(DiceGameController::class)
            ->onlyMethods(['render', 'redirectToRoute', 'addFlash'])
            ->getMock();

        $this->controller->method('render')
            ->willReturn(new Response('rendered'));
        $this->controller->method('redirectToRoute')
            ->willReturn(new RedirectResponse('/mocked'));
        $this->controller->method('addFlash')
            ->will($this->returnCallback(function () {
            // 
        }));
    }

    public function testTestRollDices_ValidNum()
    {
        $response = $this->controller->testRollDices(5);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('rendered', $response->getContent());
    }

    public function testTestRollDices_TooMany()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Can not roll more than 99 dices!');
        $this->controller->testRollDices(100);
    }

    public function testInitCallback_SetsSessionAndRedirect()
    {
        $request = $this->createMock(Request::class);
        $request->request = $this->createMock(\Symfony\Component\HttpFoundation\ParameterBag::class);
        $request->request->method('get')->with('num_dices')->willReturn(3);

        $this->session->expects($this->exactly(4))->method('set');

        $response = $this->controller->initCallback($request, $this->session);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertStringContainsString('/mocked', $response->getTargetUrl());
    }

    public function testRoll_WithOneResetsRound()
    {
        $hand = $this->createMock(\App\Dice\DiceHand::class);
        $hand->expects($this->once())->method('roll');
        $hand->method('getValues')->willReturn([3, 1, 5]);

        $this->session->method('get')
            ->willReturnCallback(function ($key) use ($hand) {
                return match ($key) {
                    'pig_dicehand' => $hand,
                    'pig_round' => 5,
                    default => null,
                };
            });

        $this->session->expects($this->once())
            ->method('set')
            ->with('pig_round', 0);

        $response = $this->controller->roll($this->session);

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\RedirectResponse::class, $response);
        $this->assertStringContainsString('/mocked', $response->getTargetUrl());
    }

    public function testSave_UpdatesTotalAndRedirect()
    {
        $this->session->method('get')
            ->willReturnMap([
                ['pig_round', 5],
                ['pig_total', 10],
            ]);

        $this->session->expects($this->exactly(2))->method('set');

        $response = $this->controller->save($this->session);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertStringContainsString('/mocked', $response->getTargetUrl());
    }
}
