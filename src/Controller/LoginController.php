<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

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
                return $this->redirectToRoute('user_list');
            } else {
                // Vérification dans la base de données
                $entityManager = $this->getDoctrine()->getManager();
                $user = $entityManager->getRepository(User::class)->findOneBy([
                    'email' => $email,
                    'mdp' => $password, // Vous devriez utiliser un mécanisme sécurisé pour stocker les mots de passe, comme bcrypt.
                ]);
    
                if ($user !== null) {
                    // Redirection vers User_Update s'il y a une correspondance dans la base de données
                    return $this->redirectToRoute('User_Upda', ['id' => $user->getId()]);

                }
            }
        }
    
        // Affichage du formulaire de connexion
        return $this->render('user/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}




  

 




