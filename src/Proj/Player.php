<?php

namespace App\Proj;

use App\Repository\ProjRepository;
use Symfony\http\Response;

class Player
{
    public function getPlayer(
        ProjRepository $projRepository,
    ): Response {
        player->projRepository->getCurrentPlayer();
    }
}
