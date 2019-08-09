<?php

namespace App\Controller;

use App\Entity\Category;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/category", name="category.")
 */

class CategoryController extends AbstractController {
    /**
     * @Route("/", name="list", methods={"GET"})
     */
    public function index() {

        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('categories/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request) {
        $category = new Category();

        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Create',
                'attr' => [
                    'class' => 'btn btn-primary mt-3'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Succesfully created');

            return $this->redirectToRoute('category.list');
        }

        return $this->render('categories/create.html.twig', [
            'form' => $form->createView()
        ]);

    }

}