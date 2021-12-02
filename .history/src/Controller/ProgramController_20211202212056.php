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

    public function showSeasons(int $id ): Response
    {
        //Let's get the seasons for a program
        $seasons = 
    }

        return $this->render('program/show.html.twig', ['program' => $program, 'seasons' => $seasons]);
                
    }
}