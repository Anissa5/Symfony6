<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProgramRepository;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findBy(
            ['category' => 1],
            ['title' => 'ASC']
        );

        return $this->render('program/index.html.twig', [
            'website' => 'Donkey Séries',
            'programs' => $programs,

        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id, ProgramRepository $programRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);

        if ($program === null) {
        throw $this->createNotFoundException('Série introuvable.');
        }

        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }
}
