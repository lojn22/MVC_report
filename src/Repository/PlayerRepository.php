<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function findByName(string $name): ?Player
    {
        return $this->findOneBy(['name' => $name]);
    }

    public function resetPlayer(Player $player): Player
    {
        $registry = $this->getManagerRegistry();

        $player->setCurrentStage(0);
        $player->setVisitedRooms([]);
        $player->setInventory([]);

        $registry->persist($player);
        $registry->flush();

        return $player;
    }
}
