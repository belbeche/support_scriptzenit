<?php

namespace App\Controller\Back;


use App\Entity\Categorie;
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
    public function listCategorie(EntityManagerInterface $em,Request $request): Response
    {

        $categorie = new Categorie();
        $form = $this->createForm()

        return $this->render('back/categorie/list.html.twig', [
            'categorie' => $categorie,
        ]);
    }
}