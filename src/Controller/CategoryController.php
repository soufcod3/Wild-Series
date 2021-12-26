<?php
// src/Controller/CategoryController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Program;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/category/", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("", name="index") 
     */
    public function index()
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        
        return $this->render('category/index.html.twig', ['categories' => $categories]);
    }

    /**
     * Form to add a category
     * 
     * @IsGranted("ROLE_ADMIN")
     * @Route("new", name="new")
     */
    public function new(Request $request): Response
    {   
        // Create a new category object
        $category = new Category();

        // Create a form associated to the category object
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Process data: persiste & flush, redirect
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/new.html.twig', ["form" => $form->createView()]);
    }


    /**
     * @Route("{categoryName}", name="show")
     */
    public function show(string $categoryName)
    {
        // Getting the category using its name
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);

        // If ['name'] is undefined
        if (!$category) {
            throw $this->createNotFoundException(
                'Aucune catégorie nommée ' . $categoryName
            );
        }

        // Getting the program.s linked to $categoryName category
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category], ['id' => 'DESC'], 3);

    
        return $this->render('category/show.html.twig', ['category' => $category, 'programs' => $programs]);
    }
    
}