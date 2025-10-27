<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $current_stage = 0;

    #[ORM\Column(type: 'json')]
    private array $visited_rooms = [];

    #[ORM\Column(type: 'json')]
    private array $inventory = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCurrentStage(): int
    {
        return $this->current_stage;
    }

    public function setCurrentStage(int $current_stage): self
    {
        $this->current_stage = $current_stage;

        return $this;
    }

    public function getVisitedRooms(): array
    {
        return $this->visited_rooms;
    }

    public function setVisitedRooms(array $visited_rooms): static
    {
        $this->visited_rooms = $visited_rooms;

        return $this;
    }

    public function addVisitedRoom(int $roomId): self
    {
        $rooms = $this->getVisitedRooms();
        if (!in_array($roomId, $rooms)) {
            $rooms[] = $roomId;
            $this->setVisitedRooms($rooms);
        }

        return $this;
    }

    public function getInventory(): array
    {
        return $this->inventory;
    }

    public function setInventory(array $inventory): static
    {
        $this->inventory = $inventory;

        return $this;
    }

    public function addItem(string $item): static
    {
        $inventory = $this->getInventory();
        if (!in_array($item, $inventory)) {
            $inventory[] = $item;
            $this->setInventory($inventory);
        }

        return $this;
    }

    public function hasItem(string $item): bool
    {
        return in_array($item, $this->getInventory());
    }

    public function resetGame(): self
    {
        $this->current_stage = 0;
        $this->visited_rooms = [];
        $this->inventory = [];

        return $this;
    }
}
