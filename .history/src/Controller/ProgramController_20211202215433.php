<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Season;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @Route("/program/", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * @Route("", name="index")
     */ 
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
            
        return $this->render('program/index.html.twig', 
            ['programs' => $programs]
        );
    }

    /**
     * @Route("show/{id}", requirements={"id"="\d+"}, name="show")
     * @return Response
     */
    public function show(int $id): Response
    {
        // Getting the program from its id
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $id]);

        // Gettings season.s from $program
        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' => $program]);

        if (!$program) {
            throw $this->createNotFoundException(
                'Cette série est introuvable.'
            );
        }

        return $this->render("program/index.html.twig");
    }

    /**
     * @Route("show/{programId}/season/", name="show_seasons")
     */
    public function showSeasons(int $programId): Response
    {
        // Let's select the program
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $programId]);

        // Let's get the seasons for a program
        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' => $program]);
    
        return $this->render('season/index.html.twig', ['program' => $program, 'seasons' => $seasons]);
    }

    /**
     * @Route("show/{programId}/season/{seasonNumber}", name="show_season")
     */
    public function showSeason(int $programId, int $seasonId): Response
}