<?php

namespace App\Controller\Back;

use App\Entity\Article;
use App\Form\Type\ArticleType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class ArticleController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return mixed
     * @Route("/admin/show", name="admin_show")
     */
    public function indexArticle()
    {
        $article = $this->entityManager->getRepository(Article::class)->findAll();

        return $this->render('back/article/index.html.twig', [
            'articles' => $article,
        ]);
    }

    /**
     * @Route("/create", name="create_article")
     *
     * @param Request $request
     * @return Response
     */
    public function newArticle(Request $request): Response
    {
        $article = new Article();
        $article->setCreatedAt(new DateTime('now'));

        $form = $this->createForm(ArticleType::class, $article);



        if ($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                    /*$form->getData();*/
                    /*dd($form->getData());*/

                    $this->entityManager->persist($article);
                    $this->entityManager->flush();

                    return $this->redirectToRoute('back_home');
            }
        }

        return $this->render('back/article/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("show/{id}", name="show_Article")
     * @param Article $article
     * @return Response
     */
    public function showArticle(Article $article): Response
    {

        if (!$article) {
            throw $this->createNotFoundException(
                "Aucun article n'a été trouver avec l'id n°".$article->getId()
            );
        }
        $article = $this->entityManager->getRepository(Article::class)->find($article->getId());

        /*$comments*/

        return $this->render('back/article/list.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/edit/{id}", name="article_edit")
     * @param Article $article
     * @param Request $request
     * @return Response
     */

    public function UpdateArticle(Article $article,Request $request): Response
    {
        $article = $this->entityManager->getRepository(Article::class)->find($article->getId());
        $form = $this->createForm(ArticleType::class, $article);

        if ($request->isMethod('POST')){
            $form->handleRequest($request);

            $this->entityManager->persist($article);
            $this->entityManager->flush();

            return $this->redirectToRoute('show_Article', ['id' => $article->getId()]);
        }

        return $this->render('back/article/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/delete/{id}", name="delete_article")
     * @param Article $article
     * @return mixed
     */
    public function deleteArticle(Article $article): Response
    {
        $this->entityManager->remove($article);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_show', [], Response::HTTP_SEE_OTHER);
    }
}
