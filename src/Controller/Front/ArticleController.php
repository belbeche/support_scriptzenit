<?php
// app/Controller/Front;

namespace App\Controller\Front;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Image;
use App\Form\Article\Type\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController
{
    /**
     * @Route("/articles/{slug}", name="front_articles_show")
     * @return Response
     */
    public function show(
        EntityManagerInterface $entityManager,
        Request $request,
        Article $article,
        $slug
    )
    {
        // to get the comment in the article, we assign it in the variable $article
        $article = $entityManager->getRepository(Article::class)->find($article->getId());

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $comment->setArticle($article);
                $comment->setUser($this->getUser());
                /*dump($this->getUser());die();*/
                $entityManager->persist($comment);
                $entityManager->flush();

                return $this->redirectToRoute('front_articles_show', ['slug' => $slug]);
            }
        }

        $comments = $entityManager->getRepository(Comment::class)->findBy(
            [
                'article' => $article,
                'active' => true,
            ],
            ['id' => 'DESC']
        );

        return $this->render('front/article/show.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'comments' => $comments
        ]);
    }
}
