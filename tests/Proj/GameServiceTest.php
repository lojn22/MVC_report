<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Entity\Player;
use App\Repository\PlayerRepository;
use App\Repository\RoomRepository;
use App\Service\GameService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Room;

/**
 * Test cases for class.
 */
class GameServiceTest extends TestCase
{
    private $playerRepository;
    private $roomRepository;
    private $doctrine;
    private $entityManager;
    private $session;
    private $requestStack;
    private GameService $gameService;


    public function setUp(): void
    {
        $this->playerRepository = $this->createMock(PlayerRepository::class);
        $this->roomRepository = $this->createMock(RoomRepository::class);
        $this->entityManager = $this->getMockBuilder(\stdClass::class)
                                    ->addMethods(['persist','flush'])
                                    ->getMock();
        $this->doctrine = $this->createMock(ManagerRegistry::class);
        $this->doctrine->method('getManager')->willReturn($this->entityManager);

        $this->session = $this->createMock(SessionInterface::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->requestStack->method('getSession')->willReturn($this->session);

        $this->gameService = new GameService(
            $this->playerRepository,
            $this->roomRepository,
            $this->doctrine,
            $this->requestStack
        );
    }

    public function testGetPlayer_ReturnsExistingPlayer()
    {
        $player = new Player();
        $this->session->method('get')->with('player_id')->willReturn(1);
        $this->playerRepository->method('find')->with(1)->willReturn($player);

        $result = $this->gameService->getPlayer();

        $this->assertSame($player, $result);
    }

    public function testGetPlayer_CreatesNewPlayerIfNotExists()
    {
        $this->session->method('get')->with('player_id')->willReturn(null);
        $this->entityManager->expects($this->once())->method('persist');
        $this->entityManager->expects($this->once())->method('flush');
        $this->session->expects($this->once())->method('set');

        $player = $this->gameService->getPlayer();

        $this->assertInstanceOf(Player::class, $player);
        $this->assertEquals('Lorelai', $player->getName());
    }

    public function testEnterHouse_TooEarly()
    {
        $player = new Player();
        $player->setCurrentStage(0);
        $room = new Room();
        $room->setStage(2);
        $room->setName('HouseA');

        $this->session->method('get')->willReturn(1);
        $this->playerRepository->method('find')->willReturn($player);
        $this->roomRepository->method('findOneBy')->willReturn($room);

        $result = $this->gameService->enterHouse(2);

        $this->assertEquals('You are too early, come back later.', $result['message']);
    }

    public function testEnterHouse_AlreadyVisited()
    {
        $player = new Player();
        $player->setCurrentStage(0);
        $player->setVisitedRooms(['HouseA']);
        $room = new Room();
        $room->setStage(1);
        $room->setName('HouseA');

        $this->session->method('get')->willReturn(1);
        $this->playerRepository->method('find')->willReturn($player);
        $this->roomRepository->method('findOneBy')->willReturn($room);

        $result = $this->gameService->enterHouse(1);

        $this->assertStringContainsString('already visited', $result['message']);
    }

    public function testMakeChoice_UpdatesPlayerAndInventory()
    {
        $player = new Player();
        $player->setCurrentStage(0);
        $player->setInventory([]);
        $player->setVisitedRooms([]);
        $room = new Room();
        $room->setStage(1);
        $room->setName('HouseA');

        $this->session->method('get')->willReturn(1);
        $this->playerRepository->method('find')->willReturn($player);
        $this->roomRepository->method('findOneBy')->willReturn($room);
        $this->roomRepository->method('findAll')->willReturn([$room]);
        $this->entityManager->expects($this->once())->method('persist');
        $this->entityManager->expects($this->once())->method('flush');

        $result = $this->gameService->makeChoice(1, 'napkin');

        $this->assertEquals(['napkin'], $player->getInventory());
        $this->assertEquals(['HouseA'], $player->getVisitedRooms());
        $this->assertEquals(1, $player->getCurrentStage());
        $this->assertTrue($result['gameOver']);
    }

    public function testCheckGameResult()
    {
        // WIN: Alla rätt
        $playerWin = new Player();
        $playerWin->setInventory(['napkin', 'sookieI', 'coffee', 'fork']);

        $resultWin = $this->gameService->checkGameResult($playerWin);
        $this->assertEquals('win', $resultWin['status']);
        $this->assertStringContainsString('true Gilmore', $resultWin['message']);

        // PARTIAL: Delvis rätt
        $playerPartial = new Player();
        $playerPartial->setInventory(['fork', 'turkey', 'soda']);

        $resultPartial = $this->gameService->checkGameResult($playerPartial);
        $this->assertEquals('partial', $resultPartial['status']);
        $this->assertStringContainsString('At least you got the fork', $resultPartial['message']);

        // LOSE: Fel, ingen fork
        $playerLose = new Player();
        $playerLose->setInventory(['napkin', 'coffee']);

        $resultLose = $this->gameService->checkGameResult($playerLose);
        $this->assertEquals('lose', $resultLose['status']);
        $this->assertStringContainsString('someone else forked it', $resultLose['message']);
    }

}
