<?php

namespace App\Controller\Back;


use App\Entity\Article;
use App\Entity\Categorie;
use App\Form\Article\Type\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/admin/categorie", name="back_categories_list")
     * @return Response
     */
    public function listCategorie(
        EntityManagerInterface $em,
        Request $request
    ): Response
    {

        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);

        if($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                /*$categorie->addArticle($article);*/
                /*$test = $form->getData();
                dump($test);*/

                $em->persist($categorie);
                $em->flush();

                /*return $this->redirectToRoute('back_categories_list');*/
            }
        }


        return $this->render('back/categorie/list.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}