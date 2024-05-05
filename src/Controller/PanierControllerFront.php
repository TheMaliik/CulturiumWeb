<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Form\PanierType;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart')]
class PanierControllerFront extends AbstractController
{
    
   
    #[Route('/', name: 'app_cart_index', methods: ['GET'])]
    public function index(PanierRepository $panierRepository): Response
    {
        return $this->render('GestCommande/index.htm', [
            'controller_name' => 'PanierControllerFront.php',
        ]);
    }
    
    #[Route('/{id}/delete', name: 'app_panier_delete')]
    public function delete($id, PanierRepository $repository,EntityManagerInterface $entityManager)
        {
            $Panier = $repository->find($id);
    
            if (!$Panier) {
                throw $this->createNotFoundException('Panier vide');
            }
    
            // Utilisez l'EntityManager (via $entityManager) pour supprimer l'entité
            $entityManager->remove($Panier);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_cart_index');
        }  

}
