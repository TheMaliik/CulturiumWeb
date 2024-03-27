<?php

namespace App\Controller;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface; 
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;
use App\Controller\TwiliosmsController;

use Psr\Log\LoggerInterface;

class UserController extends AbstractController
{

    private $managerRegistry;
    private $entityManager;
    private $slugger;
    private $twilioClient;

    public function __construct(ManagerRegistry $managerRegistry, EntityManagerInterface $entityManager, SluggerInterface $slugger , LoggerInterface $logger, Client $twilioClient)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
        $this->logger = $logger;
        $this->twilioClient = $twilioClient;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, Client $twilioClient , LoggerInterface $logger): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload
            $imageFile = $form->get('image')->getData();
          
            if ($imageFile) {
                // Generate a unique name for the file
                $imageName = md5(uniqid()).'.'.$imageFile->guessExtension();

                // Move the file to the directory where images are stored
                $imageFile->move(
                    $this->getParameter('user_directory'),
                    $imageName
                );

                // Set the complete image path in the user entity
                $imagePath = $this->getParameter('user_directory').'/'.$imageName;
                $user->setImage($imagePath);
            }

            // Persist the user to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();



            $fullName =  $form->get('fullname')->getData();
            $email = $form->get('email')->getData();
            $mdp =  $form->get('mdp')->getData();



           // Get telephone number from the form
$tel = '+216' . $form->get('tel')->getData();
$user->setTel($tel);

            try {
                // Send SMS to the registered user
                $toNumber = $user->getTel();
                $fromNumber = '+15177818511';
        
                $message = $twilioClient->messages->create(
                    $toNumber,
                    [
                        'from' => $fromNumber,
                        'body' => 'Hello ' . $fullName . ' ! You have been successfully registered. Your email is: ' . $email . ' and your password is: ' . $mdp,
        ]
                );
                
                $logger->info('SMS sent with ID: ' . $message->sid);
            } catch (\Exception $e) {
                $logger->error('Failed to send SMS: ' . $e->getMessage());
            }

            // Redirect to another page after successful registration
            return $this->redirectToRoute('registration_success');
        }

        return $this->render('user/register.html.twig', [
            'registration_form' => $form->createView(),
        ]);
    }





    #[Route('/users', name: 'user_list')]
    public function userList(Request $request, UserRepository $userRepository): Response
    {
        // Get the sort option from the request query parameters
        $sortBy = $request->query->get('sort');
        
        // If sorting by email is requested, fetch users sorted by email
        if ($sortBy === 'email') {
            $users = $userRepository->findAllSortedByEmail();
        } else {
            // Otherwise, fetch all users
            $users = $userRepository->findAll();
        }
        
        // Render the template and pass the users to it
        return $this->render('user/user_list.html.twig', [
            'users' => $users,
        ]);
    }
    




/*
    #[Route('/users', name: 'user_list')]
    public function userList(): Response
    {
        // Fetch all users from the database
        $userRepository = $this->managerRegistry->getRepository(User::class);
        $users = $userRepository->findAll();

        // Render the template and pass the users to it
        return $this->render('user/user_list.html.twig', [
            'users' => $users,
        ]);
    }
*/





    #[Route('/user/{id}/update', name: 'update_user')]
    public function update(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository(User::class);

        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $form = $this->createForm(RegisterUserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'User updated successfully.');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/update.html.twig', [
            'user' => $user,
            'registration_form' => $form->createView(),
        ]);
    }



    private $logger;

  

    #[Route('/user/{id}/delete', name: 'delete_user', methods: ['POST'])]
    public function delete(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository(User::class);
    
        $user = $userRepository->find($id);
       
                $entityManager->remove($user);
                $entityManager->flush();
    
                $this->addFlash('success', 'User deleted successfully.');
           
    
        return $this->redirectToRoute('user_list');
    }




    #[Route('/user/{id}/reactivate', name: 'reactivate_user', methods: ['POST'])]
    public function reactivateUser(int $id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        // Reactivate the user
        $user->setIsBlocked(false);
        $user->setIsApproved(true); // Set isApproved to true
        $entityManager->flush();

        // Redirect back to the user list
        return $this->redirectToRoute('user_list');
    }







// Tri recherche ........
public function sortByEmail(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAllSortedByEmail();

        return $this->render('user/search.html.twig', [
            'users' => $users,
        ]);
    }






// User Interface Update have ban button
#[Route('/user/{id}/updateUser', name: 'User_Upda')]
    public function updateUser(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository(User::class);

        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $form = $this->createForm(RegisterUserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'User updated successfully.');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/user_Card.html.twig', [
            'user' => $user,
            'registration_form' => $form->createView(),
        ]);
    }



// Ban User
    #[Route('/user/{id}/Ban', name: 'ban_user', methods: ['POST'])]
    public function banUser(int $id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        // Ban the user
        $user->setIsBlocked(true);// Set isApproved to true
        $user->setIsApproved(false); 
        $entityManager->flush();

        // Redirect back to the user list
        return $this->redirectToRoute('user_list');
    }








}




