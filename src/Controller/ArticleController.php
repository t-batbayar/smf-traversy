<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * @Route("/article", name="article.")
 */

class ArticleController extends AbstractController {
    /**
     * @Route("/", name="list", methods={"GET"})
     */
    public function index() {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('articles/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/article/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request) {
        $article = new Article();

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('body', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('category', EntityType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'class' => Category::class
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
            $article = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('articles/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/article/edit/{id}", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, $id) {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('body', TextareaType::class, [
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('category', EntityType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'class' => Category::class
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Update',
                'attr' => ['class' => 'btn btn-primary mt-3']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('articles/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/article/delete/{id}", name="delete", methods="DELETE")
     */
    public function delete($id) {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }


    /**
     * @Route("/article/{id}", name="show")
     */
    public function show($id) {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        return $this->render('articles/show.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/article/save")
     */
    public function save() {
        $entityManager = $this->getDoctrine()->getManager();

        $article = new Article();
        $article->setTitle('Article One');
        $article->setBody('This is the body for article one');

        $entityManager->persist($article);
        $entityManager->flush();

        return new Response('Saved an article with the id of ' . $article->getId());
    }
}