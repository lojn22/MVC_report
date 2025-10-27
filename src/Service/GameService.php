<?php

namespace App\Service;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use App\Repository\RoomRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

// use Psr\Log\LoggerInterface;

class GameService
{
    private PlayerRepository $playerRepository;
    private RoomRepository $roomRepository;
    private $entityManager;
    private $session;

    public function __construct(
        PlayerRepository $playerRepository,
        RoomRepository $roomRepository,
        ManagerRegistry $doctrine,
        RequestStack $requestStack,
    ) {
        $this->playerRepository = $playerRepository;
        $this->roomRepository = $roomRepository;
        $this->entityManager = $doctrine->getManager();
        $this->session = $requestStack->getSession();
    }

    public function getPlayer(): Player
    {
        $playerId = $this->session->get('player_id');

        if ($playerId) {
            $player = $this->playerRepository->find($playerId);
            if ($player) {
                return $player;
            }
        }

        $player = new Player();
        $player->setName('Lorelai');
        $player->setCurrentStage(0);
        $player->setVisitedRooms([]);
        $player->setInventory([]);

        $this->entityManager->persist($player);
        $this->entityManager->flush();

        $this->session->set('player_id', $player->getId());

        return $player;
    }

    public function resetPlayer(): void
    {
        $this->session->remove('player_id');
    }

    public function showHouses(): array
    {
        return $this->roomRepository->getAllOrdered();
    }

    public function enterHouse(
        int $stage,
    ): array {
        $player = $this->getPlayer();
        $room = $this->roomRepository->findOneBy(['stage' => $stage]);

        $nextStage = $player->getCurrentStage() + 1;

        // Om spelaren väljer huset för tidigt
        if ($room->getStage() > $nextStage) {
            return [
                'room' => $room,
                'player' => $player,
                'message' => 'You are too early, come back later.',
            ];
        }

        // Om spelaren väljer huset igen efter att redan har varit där.
        if (in_array($room->getName(), $player->getVisitedRooms())) {
            return [
                'room' => $room,
                'player' => $player,
                'message' => 'You alredy visited this house. Hurry, the next dinner is waiting!',
            ];
        }

        // Om det är rätt hus i ordning som ska spelas
        return [
            'room' => $room,
            'player' => $player,
            'message' => null,
        ];
    }

    public function makeChoice(
        int $stage,
        string $choice,
    ): array {
        $player = $this->getPlayer();
        $room = $this->roomRepository->findOneBy(['stage' => $stage]);

        // Kontroll om det är huset i den ordning som det ska spelas
        if ($player->getCurrentStage() + 1 !== $stage) {
            return [
                'gameOver' => false,
                'player' => $player,
                'message' => 'You cannot eat here yet',
            ];
        }

        // Uppdatera inventory
        $inventory = $player->getInventory();
        if (!in_array($choice, $inventory)) {
            $inventory[] = $choice;
            $player->setInventory($inventory);
        }

        // Lägg till att spelaren har spelat rummet
        $visited = $player->getVisitedRooms();
        if (!in_array($room->getName(), $visited)) {
            $visited[] = $room->getName();
            $player->setVisitedRooms($visited);
        }

        // Uppdatera currentStage
        $player->setCurrentStage($stage);

        // Kolla om alla rum är spelade
        $totalRooms = count($this->roomRepository->findAll());
        $gameOver = count($visited) >= $totalRooms;

        $this->entityManager->persist($player);
        $this->entityManager->flush();

        if ($gameOver) {
            $result = $this->checkGameResult($player);
            $this->session->set('final_result', $result);
        }

        return [
            'gameOver' => $gameOver,
            'player' => $player,
        ];
    }

    public function checkGameResult(
        Player $player,
    ): array {
        $items = $player->getInventory();
        $correctItems = ['napkin', 'sookieI', 'coffee', 'fork'];

        $missing = array_diff($correctItems, $items);
        $extra = array_diff($items, $correctItems);

        if (empty($missing) && empty($extra)) {
            return [
                'status' => 'win',
                'message' => 'You are a true Gilmore! Fork in hand, desseert on the horizon!',
            ];
        }

        if (in_array('fork', $items)) {
            $mistakes = [];
            if (in_array('turkey', $items)) {
                $mistakes[] = 'Too much turky. ';
            }
            if (in_array('jackson', $items)) {
                $mistakes[] = 'You should have listen to Sookie. ';
            }
            if (in_array('soda', $items)) {
                $mistakes[] = "You didn't drink enough coffee. ";
            }

            return [
                'status' => 'partial',
                'message' => implode('', $mistakes).'At least you got the fork but your to full for desert.',
            ];
        }

        return [
            'status' => 'lose',
            'message' => 'You stare att the desert... but someone else forked it first. Typical.',
        ];
    }

    public function getSession()
    {
        return $this->session;
    }
}
