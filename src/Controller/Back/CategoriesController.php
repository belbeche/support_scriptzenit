<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    /**
     * @Route("/back/categories", name="app_back_categories")
     */
    public function index(): Response
    {
        return $this->render('back/categories/index.html.twig', [
            'controller_name' => 'CategoriesController',
        ]);
    }
}
