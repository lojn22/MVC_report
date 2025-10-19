<?php

namespace App\Controller;

use App\Service\GameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjController extends AbstractController
{
    private GameService $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    #[Route('/proj', name: 'proj_home')]
    public function index(): Response
    {
        // $player = $this->gameService->initGame();

        return $this->render('proj/index.html.twig', [
            'controller_name' => 'ProjController',
        ]);

        // $player = $this->gameService->getPlayer();
        // return $this->redirectToRoute('proj_start');
    }

    #[Route('proj/restart', name: 'proj_restart')]
    public function restart(): Response
    {
        $this->gameService->resetPlayer();
        return $this->redirectToRoute('proj_home');
    }
    
    #[Route('/proj/about', name: 'proj_about')]
    public function about(): Response
    {
        return $this->render('proj/about.html.twig', [
            'controller_name' => 'ProjController',
        ]);
    }

    #[Route('proj/town', name: 'proj_start')]
    public function townView(): Response 
    {
        // $player = $this->gameService->initGame();
        $player = $this->gameService->getPlayer();
        $rooms = $this->gameService->showHouses();

        return $this->render('proj/town.html.twig', [
            'player' => $player,
            'rooms' => $rooms,
            'message' => "Lane's is always superearly so that's easy to catch (Mrs Kim). Sookie's is midafternoon. We've to go to my parent's
            or be brought up on war crimes. Luke's the toughie. We can squees him in between Sookie and Emily and Richard."
        ]);
    }

    #[Route('proj/room/{stage}', name: 'proj_room')]
    public function enterHouses(
        int $stage,
    ): Response {
        $result = $this->gameService->enterHouse($stage);

        return $this->render('proj/room.html.twig', [
            'room' => $result['room'],
            'player' => $result['player'],
            'message' => $result['message']
        ]);
    }

    #[Route('proj/room/{stage}/action', name: 'proj_action')]
    public function performAction(
        int $stage,
    ): Response {
        $result = $this->gameService->updateFullness($stage);

        if ($result['gameOver']){
            return $this->redirectToRoute('proj_final');
        }

        return $this->redirectToRoute('proj_start');
    }

    #[Route('proj/final', name: 'proj_final')]
    public function final(): Response 
    {
        $final = $this->gameService->determineEnding();

        return $this->render('proj/final.html.twig', [
            'final' => $final,
        ]);
    }
}