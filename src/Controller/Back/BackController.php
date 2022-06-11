<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackController extends AbstractController
{
    /**
     * @Route("/admin", name="back_home")
     * @return Response
     */
    public function home(): Response
    {
        return $this->render('back/home.html.twig');
    }
}
