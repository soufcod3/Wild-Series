<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\ProgramType;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("new", name="new")
     */
    public function new(Request $request): Response
    {
        $program = new Program();

        $form = $this->createForm(ProgramType::class, $program);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();
            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', ['form' => $form->createView()]);
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
        
        $actors = $program->getActors();
        
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

        return $this->render("program/show.html.twig", ['program' => $program, 'seasons' => $seasons, 'actors' => $actors]);
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
     * @Route("{programId}/season/{seasonNumber}", name="season_show")
     */
    public function showSeason(Program $programId, Season $seasonNumber): Response
    {
        return $this->render('season/show.html.twig', ['program' => $programId, 'season' => $seasonNumber]);
    }

    /**
     * @Route("{programId}/season/{seasonNumber}/episode/{episodeId}", name="episode_show")
     */
    public function showEpisode(Program $programId, Season $seasonNumber, Episode $episodeId): Response
    {
        return $this->render('episode/show.html.twig', ['program' => $programId, 'season' => $seasonNumber, 'episode' => $episodeId]);
    }



}