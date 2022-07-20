<?php

namespace App\Controller\Front;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="front_home")
     */
    public function home(EntityManagerInterface $entityManager)
    {

        $articles = $entityManager->getRepository(Article::class)->findBy(['active' => true], ['id' => 'desc'], 10, null);
        if($articles == true){
            $this->addFlash('warning', 'Tous les articles ont était chargé !');
        }
        return $this->render('front/home.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/blog", name="front_blog")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function blog(EntityManagerInterface $entityManager, PaginatorInterface $paginator,Request $request)
    {
        $data = $entityManager->getRepository(Article::class)->findBy(['active' => true]);

        $articles = $paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            12
        );

        return $this->render('front/blog.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/contact", name="front_contact")
     * @return Response
     */
    public function contact()
    {
        return $this->render('front/contact/contact.html.twig');
    }
}
