<?php

namespace App\Controller;
use App\Entity\Panier;
use App\Form\PanierType;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('index.htm', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/shop', name: 'app_shop')]
    public function index2(): Response
    {
        return $this->render('FrontCart/shop.html.twig',[
            'shop' => 'shop'
        ]);
    }
    #[Route('/cart', name: 'app_cart')]
    public function index3(): Response
    {
        return $this->render('FrontCart/Cart.html.twig',[
            'shop' => 'shop'
        ]);
    }
    
   
    #[Route('/carttest', name: 'app_show_index', methods: ['GET'])]
    public function index5(PanierRepository $panierRepository): Response
    {
        return $this->render('FrontCart/Carttest.html.twig', [
            'paniers' => $panierRepository->findAll(),
        ]);
        
    }
}
