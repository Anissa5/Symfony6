<?php

namespace App\Controller;

use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\SeasonRepository;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id, ProgramRepository $programRepository): Response
    {
        $program = $programRepository->findOneById($id);

        if ($program === null) {
            throw $this->createNotFoundException('Série introuvable.');
        }

        $seasons = $program->getSeasons();

        return $this->render('program/show.html.twig', [
            'program' => $program,
             'seasons' => $seasons,
        ]);
    }

    #[Route('/{programId}/seasons/{seasonId}', name: 'season_show', requirements: ['programId' => '\d+', 'seasonId' => '\d+'],
    methods: ['GET'])]
    public function showSeason(
        int $programId,
        int $seasonId,
        ProgramRepository $programRepository,
        SeasonRepository $seasonRepository
        ): Response {
        $program = $programRepository->findOneById($programId);

        if ($program === null) {
            throw $this->createNotFoundException('Série introuvable.');
        }

        $season = $seasonRepository->findOneBy([
            'id' => $seasonId,
            'program' => $program,
        ]);

        if ($season === null) {
            throw $this->createNotFoundException(
                'Saison introuvable pour cette série.'
            );
        }

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }
}
