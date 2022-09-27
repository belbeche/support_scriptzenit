<?php

namespace App\Controller\Front;

use App\Data\SearchData;
use App\Entity\Article;
use App\Entity\Category;
use App\Form\Model\SearchModel;
use App\Form\Search\SearchType;
use App\Repository\ArticleRepository;
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
    public function home(EntityManagerInterface $entityManager, Request $request,PaginatorInterface $paginator,ArticleRepository $articleRepository)
    {

        $data = $entityManager->getRepository(Article::class)->findBy(['isPublished' => true],['id' => 'desc']);

        $articles = $paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            10
        );

        $categories = $entityManager->getRepository(Category::class)->findAll();

        /*$articles = $articleRepository->findSearch($search,$paginator);*/

        /*if($request->isMethod('POST'))
        {
            if($formSearch->isSubmitted() && $formSearch->isValid())
            {
                $entityManager->persist($users);
                $entityManager->persist($search);
                $entityManager->flush();

                $this->addFlash('success', 'Merci de votre inscription ! Vous serez informé sous peu de nos dernières actualités.');
                return $this->redirectToRoute('front_home');
            }
        }*/

        return $this->render('front/home.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
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

    /**
     * @Route("/rechercher", name="front_search")
     */
    public function search(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator)
    {
        $searchModel = new SearchModel();
        $form = $this->createForm(SearchType::class, $searchModel);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $entityManager->getRepository(Article::class)->findBySearch($searchModel);

                $articles = $paginator->paginate(
                    $data,
                    $request->query->getInt('page',1),
                    10
                );
                return $this->render('front/result.html.twig', [
                        'articles' => $articles,
                ]);
            }
        }

        return $this->render('front/includes/search.html.twig', [
                'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/blog/categorie/{name}", name="front_category")
     * @return Response
     */
    public function Categories(Category $category,EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $category = $entityManager->getRepository(Category::class)->find($category);

        $articles = $entityManager->getRepository(Article::class)->findByCategoryName($category->getName());

        return $this->render('front/category/home.html.twig', [
            'categorie' => $category,
            'articles' => $articles,
        ]);
    }
}
