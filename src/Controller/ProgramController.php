<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Repository\ProgramRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Episode;
use App\Form\ProgramType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

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

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
        public function new(Request $request, EntityManagerInterface $entityManager): Response
        {
            $program = new Program();
            $form = $this->createForm(ProgramType::class, $program);
            $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($program);
                    $entityManager->flush();

                    return $this->redirectToRoute('program_index');
                }

            return $this->render('program/new.html.twig', [
                'form' => $form->createView(),
            ]);
        }

    #[Route(
        '/{id}',
        name: 'show',
        requirements: ['id' => '\d+'],
        methods: ['GET']
    )]
    public function show(
        #[MapEntity(id: 'id')]
        Program $program
    ): Response {
        $seasons = $program->getSeasons();

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
        ]);
    }

    #[Route(
        '/{programId}/seasons/{seasonId}',
        name: 'season_show',
        requirements: ['programId' => '\d+', 'seasonId' => '\d+'],
        methods: ['GET']
    )]
    public function showSeason(
        #[MapEntity(id: 'programId')]
        Program $program,

        #[MapEntity(id: 'seasonId')]
        Season $season
    ): Response {
        if ($season->getProgram() !== $program) {
            throw $this->createNotFoundException(
                'Saison introuvable pour cette série.'
            );
        }

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

    #[Route(
        '/{programId}/season/{seasonId}/episode/{episodeId}',
        name: 'episode_show',
        requirements: [
            'programId' => '\d+',
            'seasonId' => '\d+',
            'episodeId' => '\d+',
        ],
        methods: ['GET']
    )]
    public function showEpisode(
        #[MapEntity(id: 'programId')]
        Program $program,

        #[MapEntity(id: 'seasonId')]
        Season $season,

        #[MapEntity(id: 'episodeId')]
        Episode $episode
    ): Response {
        if ($season->getProgram() !== $program) {
            throw $this->createNotFoundException(
                'Saison introuvable pour cette série.'
            );
        }

        if ($episode->getSeason() !== $season) {
            throw $this->createNotFoundException(
                'Épisode introuvable pour cette saison.'
            );
        }

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}
