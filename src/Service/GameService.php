<?php

namespace App\Service;

use App\Entity\Player;
use App\Entity\Room;
use App\Repository\RoomRepository;
use App\Repository\PlayerRepository;
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
        RequestStack $requestStack
    )
    {
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
        $player->setFullness(0);
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
        int $stage
        ): array
    {
        $player = $this->getPlayer();
        $room = $this->roomRepository->findOneBy(['stage' => $stage]);

        $nextStage = $player->getCurrentStage() + 1;

        //Om spelaren väljer huset för tidigt
        if ($room->getStage() > $nextStage) {
            return [
                'room' => $room,
                'player' => $player,
                'message' => 'You are too early, come back later.'
            ];
        }

        //Om spelaren väljer huset igen efter att redan har varit där.
        if (in_array($room->getName(), $player->getVisitedRooms())) {
            return [
                'room' => $room,
                'player' => $player,
                'message' => 'You alredy visited this house. Hurry, the next dinner is waiting!'
            ];
        }

        //Om det är rätt hus i ordning som ska spelas   
        return [
            'room' => $room,
            'player' => $player,
            'message' => null
        ];
    }

    public function updateFullness(
        int $stage
        ): array
        {
        $player = $this->getPlayer();
        $room = $this->roomRepository->findOneBy(['stage' => $stage]);

        //Kontroll om det är huset i den ordning som det ska spelas
        if ($player->getCurrentStage() + 1 !== $stage) {
            return [
                'gameOver' => false,
                'player' => $player,
                'message' => 'You cannot eat here yet'
            ];
        }

        $visited = $player->getVisitedRooms();
        $visited[] = $room->getName();
        $player->setVisitedRooms($visited);
        
        $newFullness = $player->getFullness() + $room->getFullnessGain();
        $player->setFullness($newFullness);
        $player->setCurrentStage($stage);

        $this->entityManager->persist($player);
        $this->entityManager->flush();
        
        $gameOver = $newFullness >= 100;

        return [
            'gameOver' => $gameOver,
            'player' => $player
        ];
    }
}
