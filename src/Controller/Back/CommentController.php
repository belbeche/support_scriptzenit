<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="comments_home")
     * @return Response
     */
    public function listComments(): Response
    {
        return $this->render('back/comment/list.html.twig');
    }
}
