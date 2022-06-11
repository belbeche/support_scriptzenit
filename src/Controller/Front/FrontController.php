<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="front_home")
     * @return Response
     */
    public function home()
    {
        return $this->render('front/home.html.twig', []);
    }
}
