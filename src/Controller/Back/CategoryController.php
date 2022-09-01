<?php

namespace App\Controller\Back;

use App\Entity\Category;
use App\Form\Article\Type\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/categorie/listes", name="back_categories_list")
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function list(
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $data = $em->getRepository(Category::class)->findAll();

        $category = $paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            12
        );

        return $this->render('back/category/list.html.twig', [
            'categories' => $category,
        ]);
    }
    /**
     * @Route("/admin/categorie/ajouter", name="back_category_add")
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(
        EntityManagerInterface $em,
        Request $request
    ): Response
    {

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        if($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                /*$category->addArticle($category->);*/

                $em->persist($category);
                $em->flush();

                return $this->redirectToRoute('back_categories_list');
            }
        }


        return $this->render('back/category/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/categorie/afficher/{id}", name="back_categories_show")
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(EntityManagerInterface $entityManager,$id)
    {

        $categorie = $entityManager->getRepository(Category::class)->find($id);

        return $this->render('back/category/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    /**
     * @Route("/admin/categorie/edit/{id}", name="back_categories_edit")
     * @param Request $request
     * @return Response
     */
    public function edit(
        $id,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $category = $entityManager->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryType::class, $category);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid())
            {
                $entityManager->persist($category);
                $entityManager->flush();

                return $this->redirectToRoute('back_categories_list');
            }
        }

        return $this->render('back/category/edit.html.twig', [
            'form' => $form->createView(),
            'categorie' => $category,
        ]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @Route("/remove/{id}", name="back_categories_remove")
     */
    public function remove(EntityManagerInterface $entityManager)
    {

    }
}