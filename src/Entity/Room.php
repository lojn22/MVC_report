<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $stage = 0;

    #[ORM\Column(length:300)]
    private ?string $dialogue = null;

    #[ORM\Column(length: 100)]
    private ?string $actionText = null;

    #[ORM\Column]
    private ?int $fullnessGain = 0;

    #[ORM\Column(length: 255)]
    private ?string $image = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $symbol = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $interactableItems = [];

    #[ORM\Column(type: 'boolean')]
    private bool $hasChoices = false;

    // #[ORM\Column(type: 'float')]
    // private ?float $top = 0.0;

    // #[ORM\Column(type: 'float')]
    // private ?float $left = 0.0;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $top = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $left = null;

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

    public function getStage(): ?int
    {
        return $this->stage;
    }

    public function setStage(int $stage): self
    {
        $this->stage = $stage;

        return $this;
    }

    public function getDialogue(): ?string
    {
        return $this->dialogue;
    }

    public function setDialogue(string $dialogue): self
    {
        $this->dialogue = $dialogue;

        return $this;
    }
    
    public function getActionText(): ?string
    {
        return $this->actionText;
    }

    public function setActionText(string $actionText): static
    {
        $this->actionText = $actionText;

        return $this;
    }

    public function getFullnessGain(): ?int
    {
        return $this->fullnessGain;
    }

    public function setFullnessGain(int $fullnessGain): self
    {
        $this->fullnessGain = $fullnessGain;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
    
    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $image): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getTop(): ?int //int
    {
        return $this->top;
    }

    public function setTop(?int $top): static //int
    {
        $this->top = $top;
        return $this;
    }

    public function getLeft(): ?int //int
    {
        return $this->left;
    }

    public function setLeft(?int $left): static //int
    {
        $this->left = $left;
        return $this;
    }
}
