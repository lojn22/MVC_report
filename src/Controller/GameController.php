<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Game\Game;
use App\Game\Player;
use App\Game\Bank;
use App\Card\DeckOfCards;

class GameController extends AbstractController
{
    #[Route("/game", name: "game")]
    public function home(SessionInterface $session): Response
    {
        // Initiera spelet och spara det i sessionen om det inte redan existerar
        if (!$session->has('game')) {
            $deck = new DeckOfCards();
            $deck->deckShuffle();
            // $shuffledDeck = $deck->getDeck();
            // echo "Korts ordning efter blandning:\n";
            // foreach ($shuffledDeck as $card) {
            //     echo $card->getAsString() . "\n";
            // }
            $game = new Game($deck);
            $session->set('game', $game);
        }

        return $this->render('game/home.html.twig');
    }

    #[Route("/game/documentation", name: "documentation")]
    public function document(): Response
    {
        return $this->render('game/documentation.html.twig');
    }

    #[Route("/game/play/{action}", name: "play")]
    public function play(SessionInterface $session, string $action): Response
    {
            // Hämta spelet från sessionen
            $game = $session->get('game');

            // Kontrollera om spelet finns i sessionen
            if (!$game) {
                // Omdirigera användaren till startsidan för att initiera spelet
                return $this->redirectToRoute('game');
            }

            // Kontrollera vilken åtgärd som utförs: dra kort eller stanna
            if ($action === 'draw') {
                $game->playerDrawCard();  // Spelaren drar ett kort
                if ($game->getPlayer()->isBusted()) {
                    $game->getBank()->playTurn($game->getDeck());
                    $game->setWinner('Banken'); // Banken vinner om spelaren har över 21
                }
            } elseif ($action === 'stay') {
                $game->playerStays();
                $game->bankPlay();  // Banken spelar sitt drag
                $game->determineWinner(); // Bestäm vinnaren efter att banken spelat sitt drag
            }

            // Uppdatera spelet i sessionen efter förändringar
            $session->set('game', $game);

            return $this->render('game/play.html.twig', [
                'playerHand' => $game->getPlayer()->getHand(),
                'bankHand' => $game->getBank()->getHand(),
                'winner' => $game->getWinner(),
                'game' => $game
            ]);
    }

    #[Route("/game/reset", name: "game_reset")]
    public function reset(SessionInterface $session): Response
    {
        $session->remove('game');

        return $this->redirectToRoute('game');
    }
}
