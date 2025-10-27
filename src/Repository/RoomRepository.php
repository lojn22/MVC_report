<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Room::class);
    }

    public function getAllOrdered(): array
    {
        return $this->createQueryBuilder('r')
        ->orderBy('r.stage', 'ASC')
        ->getQuery()
        ->getResult();
    }

    public function findRoom(int $id): ?Room
    {
        return $this->find($id);
    }
}
