<?php

namespace App\Controller\Front;

use App\Entity\Article;
use App\Entity\Image;
use App\Entity\Newsletters;
use App\Form\Newsletters\Type\NewslettersType;
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
    public function home(EntityManagerInterface $entityManager, Request $request)
    {
        $articles = $entityManager->getRepository(Article::class)->findBy(['isPublished' => true], ['id' => 'desc'], 10, null);

        $users = new Newsletters();

        $form = $this->createForm(NewslettersType::class, $users);

        $form->handleRequest($request);

        if($request->isMethod('POST'))
        {
            if($form->isSubmitted() && $form->isValid())
            {
                $entityManager->persist($users);
                $entityManager->flush();

                $this->addFlash('success', 'Merci de votre inscription ! Vous serez informé sous peu de nos dernières actualités.');
                return $this->redirectToRoute('front_home');
            }
        }

        return $this->render('front/home.html.twig', [
            'articles' => $articles,
            'newsletters' => $form->createView(),
        ]);
    }

    /**
     * @Route("/blog", name="front_blog")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function blog(
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        Request $request
    )
    {
        $data = $entityManager->getRepository(Article::class)->findBy(['active' => true]);

        $articles = $paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            5
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
