<?php

namespace App\Controller\Back;

use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Knp\Component\Pager\PaginatorInterface;


class UserController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     * @Route("/admin/utilisateurs", name="back_users_list")
     * @IsGranted("ROLE_ADMIN")
     */
    public function list(
        Request $request,
        PaginatorInterface $paginator)
    {

        $data = $this->entityManager->getRepository(User::class)->findAll();

        $users = $paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            12
        );

        return $this->render('back/user/list.html.twig', [
            'users' => $users,
        ]);
    }
}
