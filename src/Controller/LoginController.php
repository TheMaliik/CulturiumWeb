<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse; 
use App\Form\LoginType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class LoginController extends AbstractController
{


    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('GestUser/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }



















    
    #[Route('/LoginRedirect', name: 'loredirect')]
    public function goLogin(): Response // Correct the return type declaration
    {
        // Replace 'your_route_name' with the actual route name you want to redirect to
        return $this->redirectToRoute('app_login'); // Correct the usage of redirectToRoute
    }








#[Route('/logout', name: 'app_logout')]
public function logout(Request $request, UserRepository $repo): Response
{
    // Remove user session (if stored in session)
    $request->getSession()->remove('user');

    // Create a response with cache control headers to prevent caching
    $response = $this->redirectToRoute('login');
    $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
    $response->headers->set('Pragma', 'no-cache');
    $response->headers->set('Expires', '0');

    return $response;
}







}




  

 




