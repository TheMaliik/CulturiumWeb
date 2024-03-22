<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\LoginType;



class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
public function login(Request $request, AuthenticationUtils $authenticationUtils, UserPasswordEncoderInterface $passwordEncoder): Response
{
    // Get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();

    // Last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();

    // Create a new instance of User for the form
    $user = new User();

    // Create the login form
    $form = $this->createForm(LoginType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $email = $form->get('email')->getData();
        $password = $form->get('mdp')->getData();

        // Check if the email and password match the admin credentials
        if ($email === 'melek.guesmi@esprit.tn' && $password === 'TheMaliik') {
            return $this->redirectToRoute('admin_dashboard');
        }

        // Authenticate against the database
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user || !$passwordEncoder->isPasswordValid($user, $password)) {
            // Invalid email or password, redirect back to login
            $this->addFlash('error', 'Invalid email or password.');
            return $this->redirectToRoute('app_login');
        }

        // Redirect authenticated users to a common dashboard
        return $this->redirectToRoute('common_dashboard');
    }

    // Render the login form
    return $this->render('user/login.html.twig', [
        'form' => $form->createView(),
        'last_username' => $lastUsername,
        'error'         => $error,
    ]);
}


    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function adminDashboard(): Response
    {
        // Render the admin dashboard
        return $this->render('admin/dashboard.html.twig');
    }



    #[Route('/common/dashboard', name: 'common_dashboard')]
    public function commonDashboard(): Response
    {
        // Render the common dashboard for all authenticated users
        return $this->render('common/dashboard.html.twig');
    }



}
