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

    #[ORM\Column(length: 255)]
    private ?string $image = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $symbol = null;

    #[ORM\Column(type: 'json')]
    private ?array $action_choices = [];

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
    
    public function getActionChoices(): array
    {
        return $this->action_choices;
    }

    public function setActionChoices(array $choices): static
    {
        $this->action_choices = $choices;

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

    public function getTop(): ?int
    {
        return $this->top;
    }

    public function setTop(?int $top): static
    {
        $this->top = $top;
        return $this;
    }

    public function getLeft(): ?int
    {
        return $this->left;
    }

    public function setLeft(?int $left): static
    {
        $this->left = $left;
        return $this;
    }
}
