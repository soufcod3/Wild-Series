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
     * @Route("{id}", requirements={"id"="\d+"}, name="show")
     * @return Response
     */
    public function show(Program $program): Response
    {
        // Gettings season.s from $program
        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' => $program]);
        
        if (!$seasons) {
            throw $this->createNotFoundException(
                'Cette saison n\'existe pas'
            );
        }

        if (!$program) {
            throw $this->createNotFoundException(
                'Cette série est introuvable'
            );
        }

        return $this->render("program/show.html.twig", ['program' => $program, 'seasons' => $seasons]);
    }

    /**
     * @Route("{programId}/season/", name="seasons_show")
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
     * @Route("{program_id}/season/{season_id}", name="season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_id": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"season_id": "number"}})
     */
    public function showSeason(Program $programId, Season $seasonNumber): Response
    {
        $program = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findOneBy(['id' => $programId]);

        //Select the right season for a given program
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['number' => $seasonNumber]);

        return $this->render('season/show.html.twig', ['program' => $program, 'season' => $season]);

    }

}