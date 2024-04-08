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



class LoginController extends AbstractController
{
   
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(Request $request)
    {
        // Création du formulaire
        $form = $this->createForm(LoginType::class);
    
        // Gestion de la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données du formulaire
            $formData = $form->getData();
            $email = $formData['email'];
            $password = $formData['mdp'];
    
            // Vérification des identifiants
            if ($email === 'melek.guesmi@esprit.tn' && $password === 'TheMaliik') {
                // Redirection vers userListAction du UserController si les identifiants sont corrects
                return $this->redirectToRoute('adminDashboard');
            } else {
                // Vérification dans la base de données
                $entityManager = $this->getDoctrine()->getManager();
                $user = $entityManager->getRepository(User::class)->findOneBy([
                    'email' => $email,
                    'mdp' => $password,
                    'isApproved' => true, // Check if the user is approved
                    'isBlocked' => false, // Check if the user is not blocked
                ]);
                
    
                if ($user !== null) {
                    // Redirection vers User_Update s'il y a une correspondance dans la base de données
                    return $this->redirectToRoute('UserDashboard', ['id' => $user->getId()]);

                }
            }
        }
    
        // Affichage du formulaire de connexion
        return $this->render('user/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    
    #[Route('/LoginRedirect', name: 'loredirect')]
    public function goLogin(): Response // Correct the return type declaration
    {
        // Replace 'your_route_name' with the actual route name you want to redirect to
        return $this->redirectToRoute('app_login'); // Correct the usage of redirectToRoute
    }
}




  

 




