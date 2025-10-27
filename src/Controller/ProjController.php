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
        $this->gameService->resetPlayer();
        return $this->render('proj/index.html.twig', [
            'controller_name' => 'ProjController',
        ]);
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
        $player = $this->gameService->getPlayer();
        $rooms = $this->gameService->showHouses();

        return $this->render('proj/town.html.twig', [
            'player' => $player,
            'rooms' => $rooms,
            'message' => 'Lorelai: "Lanes is always superearly so thats easy to catch." "Sookies is midafternoon." "We have to go to my parents
            or be brought up on war crimes." "Lukes the toughie." We can squeeze him in between Sookie and Emily & Richard.'
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

    #[Route('proj/room/{stage}/action/{choice}', name: 'proj_action', methods: ['POST'])]
    public function performAction(
        int $stage,
        string $choice
    ): Response {
        $result = $this->gameService->makeChoice($stage, $choice);

        if (!empty($result['gameOver']) && $result['gameOver'] === true) {
            return $this->redirectToRoute('proj_final');
        }

        return $this->redirectToRoute('proj_start');
    }

    #[Route('proj/final', name: 'proj_final')]
    public function final(): Response 
    {
        $player = $this->gameService->getPlayer();

        $session = $this->gameService->getSession();
        $result = $session->get('final_result');

        if (!$result) {
            $result = $this->gameService->checkGameResult($player);
        }

        return $this->render('proj/final.html.twig', [
            'player' => $player,
            'result' => $result
        ]);
    }
}