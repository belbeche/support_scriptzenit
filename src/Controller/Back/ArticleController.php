<?php

namespace App\Controller\Back;

use App\Entity\Article;
use App\Entity\User;
use App\Form\Article\Type\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Knp\Component\Pager\PaginatorInterface;


class ArticleController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return mixed
     * @Route("/admin/articles", name="back_articles_list")
     */
    public function list(
        Request $request,
        PaginatorInterface $paginator)
    {

        $data = $this->entityManager->getRepository(Article::class)->findAll();

        $articles = $paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            12
        );

        return $this->render('back/article/list.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/admin/articles/new", name="back_articles_new")
     *
     * @param Request $request
     * @return Response
     */
    public function new(
        Request $request
    ): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /*$article->setSlug($article->getTitle());*/

                /*$user = $this->entityManager->getRepository(User::class)->find($id);*/
                /*dd($user);*/
                $article->setUser($this->getUser());


                $this->entityManager->persist($article);
                $this->entityManager->flush();
                $this->addFlash('back_articles_ajouter_success','Article ajouté avec succéss');

                return $this->redirectToRoute('back_articles_list');
            }
        }

        return $this->render('back/article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/articles/show/{slug}", name="back_articles_show")
     * @return Response
     */
    public function show(
        Article $article
    ): Response
    {
        $article = $this->entityManager->getRepository(Article::class)->find($article);

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
                /*dd($article->getCategories());*/
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
        $article->setActive(!$article->isActive());
        $this->entityManager->persist($article);
        $this->entityManager->flush();
        if($article->isActive() == true){
            $this->addFlash('back_article_disable', "L'article ".$article->getSlug()." a bien était désactiver :[");
        }else {
            $this->addFlash('back_article_enable', "L'article ".$article->getSlug()." a bien était réactiver :]");
        }

        return $this->redirectToRoute('back_articles_list');
    }

    /**
     * @Route("/admin/articles/remove/{id}", name="back_articles_remove")
     * @param $id
     * @return Response
     */
    public function remove($id,Article $article): Response
    {
        $article = $this->entityManager->getRepository(Article::class)->find($id);

        if($article->isActive(false)){
            $this->addFlash('back_article_remove', "l\'article" . $article->getSlug() . " est supprimé avec success !");
            $this->entityManager->remove($article);
            $this->entityManager->flush();
        }else {
            $this->addFlash('back_article_non_remove', "L'article" . $article->getSlug() . " est activé, disactivez-le puis réessayer, merci.");
        }

        return $this->redirectToRoute('back_articles_list');
    }
}
