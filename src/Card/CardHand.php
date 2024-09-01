<?php

namespace App\Card;

class CardHand
{
    private $hand = [];

    public function addCard(Card $card): void
    {
        $this->hand[] = $card;
    }

    public function getNumberCards(): int
    {
        return count($this->hand);
    }

    public function getValues(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getValue();
        }

        return $values;
    }

    public function getString(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getAsString();
        }

        return $values;
    }

    public function getSum(): int
    {
        $sum = 0;
        $aces = 0;

        foreach ($this->hand as $card) {
            $value = $card->getValueAsNumber();
            $sum += $value;

            if (1 === $card->getValueAsNumber()) {
                ++$aces;
            }
        }

        while ($aces > 0 && $sum <= 11) {
            $sum += 13;
            --$aces;
        }

        return $sum;
    }

    public function resetHand(): void
    {
        $this->hand = [];
    }

    public function getCards(): array
    {
        return $this->hand;
    }
}
