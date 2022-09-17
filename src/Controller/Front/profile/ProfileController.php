<?php

namespace App\Controller\Front\profile;

use App\Entity\Image;
use App\Entity\User;
use App\Form\User\Type\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

class ProfileController extends AbstractController
{

    /**
     * @Route("/profile-page/{username}", name="front_profile")
     * @return Response
     */
    public function home(User $user,EntityManagerInterface $entityManager,Request $request): Response
    {
        $form = $this->createForm(UserFormType::class, $user);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            $user->setIsVerified(true);

            if($form->isSubmitted())
            {
                $images = $form->get('avatar')->getData();

                if($form->get('avatar')->getData() !== null){
                    foreach($images as $image){

                        // We generate a new file name
                        $fichier = md5(uniqid()).'.'.$image->guessExtension();
                        // We copy the file in the uploads folder

                        $image->move(
                            $this->getParameter('profile_directory'),
                            $fichier
                        );

                        // Create the image in the database
                        $usr = new User();
                        $usr->setAvatar($fichier);
                        $usr->setRoles($form->get('roles')->getData());

                        return $this->redirectToRoute('front_home');
                    }
                } else{
                    echo "<p> Aucune données </p>";
                }
            }

            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('front_profile', [
                'username' => $user->getUsername(),
            ]);
        }

        return $this->render('front/profile/home.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
