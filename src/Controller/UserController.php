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
use Dompdf\Dompdf;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Knp\Component\Pager\PaginatorInterface;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;

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





    #[Route('/register', name: 'app_registerr')]
    public function register(Request $request, UserRepository $userRepository,LoggerInterface $logger, FlashBagInterface $flashBag): Response 
    {
    
        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Check if a user with the submitted email already exists
            $recaptchaResponse = $request->request->get('g-recaptcha-response');
            $isRecaptchaValid = $this->isRecaptchaValid($recaptchaResponse);
            if (!$isRecaptchaValid) {
                $this->addFlash('error', 'reCAPTCHA validation failed. Please try again.');
                return $this->redirectToRoute('app_registerr');
            }
            $existingUser = $userRepository->searchByEmail($user->getEmail());
            if ($existingUser) {
                // Handle case where user with the same email already exists
                $flashBag->add('error', 'An account with this email already exists.');
                return $this->redirectToRoute('app_registerr');
            }
    
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
    
                // Set the image name in the user entity
                $user->setImage($imageName);
            }
    
            // Set user roles
            $roles = ['ROLE_USER'];
            $user->setRoles($roles);
    
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
                    $fromNumber = '+12563650805';
            
                    $message = $this->twilioClient->messages->create(
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
                return $this->redirectToRoute('login');
            }
    
            return $this->render('GestUser/user/register.html.twig', [
                'registration_form' => $form->createView(),
            ]);
        }



        private function isRecaptchaValid($recaptchaResponse)
        {
            $secretKey = '6LclU8kpAAAAAHEEfd1BGlx64KN3JGAlGOQkuPXI'; // Replace with your actual secret key
            $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
            $postData = http_build_query([
                'secret' => $secretKey, // Include the secret key in the request
                'response' => $recaptchaResponse
            ]);
        
            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => 'Content-Type: application/x-www-form-urlencoded',
                    'content' => $postData
                ]
            ]);
        
            $response = file_get_contents($recaptchaUrl, false, $context);
            $result = json_decode($response);
        
            return $result->success;
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
        return $this->render('GestUser/user/user_list.html.twig', [
            'users' => $users,
        ]);
}
    














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

            return $this->redirectToRoute('UserDashboard');
        }

        return $this->render('GestUser/user/update.html.twig', [
            'user' => $user,
            'registration_form' => $form->createView(),
        ]);
    }






    #[Route('/user/{id}/updateAdmin', name: 'update_userAdmin')]
    public function updateUserAdmin(int $id): Response
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
    
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
    
        return $this->render('GestUser/user/updateAdmin.html.twig', [
            'user' => $user,
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
           
    
        return $this->redirectToRoute('userss');
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
        return $this->redirectToRoute('userss');
    }








   

    #[Route('/sort_users_by_email', name: 'sort_users_by_email')]
    public function sortUsersByEmail(Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response
    {
        // Get sorted users by email query
        $query = $userRepository->findAllSortedByEmail();
    
        // Paginate the query results
        $pagination = $paginator->paginate(
            $query, // Query to paginate
            $request->query->getInt('page', 1), // Page number, default to 1
            10 // Limit per page
        );
    
        // Fetch all users without pagination
        $users = $userRepository->findAllSortedByEmail();
    
        // Render the template and pass the paginated users and all users to it
        return $this->render('GestUser/user/usertest.html.twig', [
            'pagination' => $pagination,
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

    // Create form for updating user fields
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

            // Set the image name in the user entity
            $user->setImage($imageName);
        }

        // Update other fields
        $entityManager->flush();

        $this->addFlash('success', 'User updated successfully.');

        return $this->redirectToRoute('UserDashboard', ['id' => $user->getId()]);
    }

    return $this->render('GestUser/user/user_Card.html.twig', [
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
        return $this->redirectToRoute('login');
}









#[Route(path: '/userss', name: 'userss')]
public function users(Request $request, UserRepository $userRepository, PaginatorInterface $paginator): Response
{
    // Get the sort option from the request query parameters
    $sortBy = $request->query->get('sort');
    
    // If sorting by email is requested, fetch users sorted by email
    if ($sortBy === 'email') {
        $usersQuery = $userRepository->findAllSortedByEmailQuery();
    } else {
        // Otherwise, fetch all users
        $usersQuery = $userRepository->findAll();
    }

    // Paginate the users
    $pagination = $paginator->paginate(
        $usersQuery, // Doctrine Query
        $request->query->getInt('page', 1), // Page number
        5 // Items per page
    );

    // Pass the paginated users and the original users list to the template
    return $this->render('GestUser/user/usertest.html.twig', [
        'pagination' => $pagination,
        'users' => $pagination->getItems(), // Pass the paginated users
    ]);
}




    #[Route(path:'/downloadUserListPdf', name: 'downloadUserListPdf')]
    public function downloadUserListPdf(): Response
    {
        // Get the list of users from the database
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepository->findAll();

        // Render the PDF template with user data
        $html = $this->renderView('GestUser/user/user_list_pdf.html.twig', [
            'users' => $users,
        ]);

        // Instantiate Dompdf
        $dompdf = new Dompdf();

        // Load HTML content
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Generate filename
        $filename = 'user_list_' . date('Ymd_His') . '.pdf';

        // Output PDF to browser for download
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]
        );
    }






    #[Route(path:'/User/Filter', name: 'filterUser')]
    public function UserListFiltred(UserRepository $userRepository): Response
    {
        $users = $userRepository->findBlockedOrApprovedUsers();

        return $this->render('GestUser/user/usertest.html.twig', [
            'users' => $users,
        ]);
    }














    #[Route('/registerEnseignant', name: 'app_registerEnseignant')]
    public function registerEnseignant(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager, Client $twilioClient , LoggerInterface $logger): Response
    {
        $role = "ROLE_ENSEIGNANT";
        $filesystem = new Filesystem();
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $recaptchaResponse = $request->request->get('g-recaptcha-response');
            $isRecaptchaValid = $this->isRecaptchaValid($recaptchaResponse);

            if (!$isRecaptchaValid) {
                $this->addFlash('error', 'reCAPTCHA validation failed. Please try again.');
                return $this->redirectToRoute('app_registerEtudiant');
            }

            



            
            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_login');


        }

        return $this->render('registration/registerEnseignant.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }












}




