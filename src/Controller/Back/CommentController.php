<?php

namespace App\Controller\Back;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="back_comments_list")
     * @return Response
     */
    public function list(EntityManagerInterface $entityManager , PaginatorInterface $paginator, Request $request): Response
    {

        $data = $entityManager->getRepository(Comment::class)->findAll();

        $comments = $paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            12
        );

        return $this->render('back/comment/list.html.twig', [
            'comments' => $comments,
        ]);
    }

    public function show(EntityManagerInterface $entityManager)
    {
        return $this->render('back/comment/show.html.twig')
    }

    public function disable()
    {
        return true;
    }
}
