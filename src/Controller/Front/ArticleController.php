<?php
// app/Controller/Front;

namespace App\Controller\Front;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController
{
    /**
     * @Route("/blog", name="article_show")
     * @return Response
     */
    public function Show(EntityManagerInterface $entityManager)
    {

        return $this->render('front/article/show.html.twig', []);
    }
}
