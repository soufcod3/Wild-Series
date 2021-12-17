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
use App\Service\Slugify;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
    public function new(Request $request, Slugify $slugify, MailerInterface $mailer): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();

            $email = (new Email())
                    ->from($this->getParameter('mailer_from'))
                    ->to($this->getParameter('mailer_to'))
                    ->subject('Une nouvelle série vient d\'etre publiée !')
                    ->html($this->renderView('program/newProgramEmail.html.twig', ['program' => $program]));
            $mailer->send($email);

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("{slug}", name="show")
     * @return Response
     */
    public function show(Program $program): Response
    {
        // Gettings season.s from $program
        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' => $program]);
        
        $actors = $program->getActors();

        if (!$program) {
            throw $this->createNotFoundException(
                'Cette série est introuvable'
            );
        }
        return $this->render("program/show.html.twig", ['program' => $program, 'seasons' => $seasons, 'actors' => $actors]);
    }

    /**
     * @Route("{slug}/season/", name="seasons_show")
     */
    public function showSeasons(Program $program): Response
    {
        // Let's select the program
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['slug' => $program]);

        // Let's get the seasons for a program
        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' => $program]);
    
        return $this->render('season/index.html.twig', ['program' => $program, 'seasons' => $seasons]);
    }

    /**
     * @Route("{slug}/season/{number}", name="season_show")
     */
    public function showSeason(Program $program, Season $season): Response
    {
        return $this->render('season/show.html.twig', ['program' => $program, 'season' => $season]);
    }

    /**
     * @Route("{program_slug}/season/{number}/episode/{episode_slug}", name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_slug": "slug"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episode_slug": "slug"}})
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {   
        return $this->render('episode/show.html.twig', ['program' => $program, 'season' => $season, 'episode' => $episode]);
    }

}