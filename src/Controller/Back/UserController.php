<?php

namespace App\Controller\Back;

use App\Entity\Image;
use App\Entity\User;

use App\Form\User\Type\UserFormType;
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
        PaginatorInterface $paginator
    ) {

        $data = $this->entityManager->getRepository(User::class)->findAll();

        $users = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('back/user/list.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/modification/utilisateur/{username}", name="back_users_edit")
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(User $user, EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(UserFormType::class, $user);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $user->setIsVerified(true);

            if ($form->isSubmitted() && $form->isValid()) {

                $images = $form->get('avatar')->getData();

                if($images){
                    foreach ($images as $image) {

                        // We generate a new file name
                        $file = md5(uniqid()) . '.' . $image->guessExtension();

                        // We copy the file in the uploads folder
                        $image->move(
                            $this->getParameter('profile_directory'),
                            $file
                        );

                        // Create the image in the database
                        $usr = new User();
                        $usr->setAvatar($file);
                        $usr->setRoles($form->get('roles')->getData());
                    }
                }

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success','L\'utilisateur '. $user->getUsername() .' a été modifié avec succès. ');

                return $this->redirectToRoute('back_users_list');

            }


            return $this->redirectToRoute('back_users_list', [
                'username' => $user->getUsername(),
            ]);
        }

        return $this->render('back/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
