<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Panier;
use App\Form\PanierType;
use App\Repository\PanierRepository;

class HomeController extends AbstractController
{
    #[Route('/admin/adminDashboard', name: 'adminDashboard')]
    public function index(Request $request): Response
    {
        
        
        return $this->render('GestUser/admin/bars.html.twig'
        );
    }
    
    #[Route('/shop', name: 'app_shop')]
    public function index2(): Response
    {
        return $this->render('GestCommande/FrontCart/shop.html.twig',[
            'shop' => 'shop'
        ]);
    }
    #[Route('/cart', name: 'app_cart')]
    public function index3(): Response
    {
        return $this->render('GestCommande/FrontCart/Cart.html.twig',[
            'shop' => 'shop'
        ]);
    }
    
   
    #[Route('/carttest', name: 'app_show_index', methods: ['GET'])]
    public function index5(PanierRepository $panierRepository): Response
    {
        return $this->render('GestCommande/FrontCart/Carttest.html.twig', [
            'paniers' => $panierRepository->findAll(),
        ]);
        
    }
    
    
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('index.htm');
    }
    


    #[Route('/user/UserDashboard/{id}', name: 'UserDashboard')]
    public function userDashboard(UserRepository $userRepository, $id): Response
    {
        // Retrieve the user entity based on $id
        $user = $userRepository->find($id);

        // Render the template, passing the user entity
        return $this->render('GestUser/user/UserDashboard.html.twig', [
            'user' => $user,
        ]);
    }

   


}
