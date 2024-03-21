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


use Psr\Log\LoggerInterface;

class UserController extends AbstractController
{


    private $managerRegistry;
    private $entityManager;
    private $slugger;

    public function __construct(ManagerRegistry $managerRegistry, EntityManagerInterface $entityManager, SluggerInterface $slugger , LoggerInterface $logger)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
        $this->logger = $logger;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request): Response
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

            // Redirect to another page after successful registration
            return $this->redirectToRoute('registration_success');
        }

        return $this->render('user/register.html.twig', [
            'registration_form' => $form->createView(),
        ]);
    }





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


}




