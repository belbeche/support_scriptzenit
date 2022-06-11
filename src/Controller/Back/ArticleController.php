<?php

namespace App\Controller\Back;

use App\Entity\Article;
use App\Form\Article\Type\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return mixed
     * @Route("/admin/articles", name="back_articles_list")
     */
    public function list()
    {
        $article = $this->entityManager->getRepository(Article::class)->findAll();

        return $this->render('back/article/list.html.twig', [
            'articles' => $article,
        ]);
    }

    /**
     * @Route("/admin/articles/new", name="back_articles_new")
     *
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $this->entityManager->persist($article);
                $this->entityManager->flush();

                return $this->redirectToRoute('back_articles_list');
            }
        }

        return $this->render('back/article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/articles/show/{id}", name="back_articles_show")
     * @param $id
     * @return Response
     */
    public function show($id): Response
    {
        $article = $this->entityManager->getRepository(Article::class)->find($id);

        return $this->render('back/article/show.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/admin/articles/edit/{id}", name="back_articles_edit")
     * @param Request $request
     * @return Response
     */
    public function edit($id, Request $request): Response
    {
        $article = $this->entityManager->getRepository(Article::class)->find($id);
        $form = $this->createForm(ArticleType::class, $article);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid())
            {
                $this->entityManager->persist($article);
                $this->entityManager->flush();

                return $this->redirectToRoute('back_articles_list');
            }
        }

        return $this->render('back/article/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }


    /**
     * @Route("/admin/articles/disable/{id}", name="back_articles_disable")
     * @param $id
     * @return mixed
     */
    public function disable($id): Response
    {
        $article = $this->entityManager->getRepository(Article::class)->find($id);

        $active = !$article->isActive();

        $article->setActive($active);

        $this->entityManager->persist($article);

        $this->entityManager->flush();

        return $this->redirectToRoute('back_articles_list');
    }
}
