<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login_authenticator")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('front_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/api/register", name="api_register")
     */
    public function register(Request $request,EntityManagerInterface $entityManager,UserPasswordHasherInterface $hasher): jsonResponse
    {

        /*$data = json_decode($request->getContent(), true);
        dump($data);
        $user = new User();

        $user
            ->setUsername($data['userFirstName'] . ' ' . $data['userLastName'])
            ->setEmail($data['userEmail'])
            ->setRoles(['ROLE_USER'])
        ;

        $user
            ->setPassword($hasher->hashpassword(
                $user,
                $data['userFirstPassword']
            ))
        ;
        /*$entityManager->persist($user);

        $entityManager->flush();*/

        return new jsonResponse('success');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
