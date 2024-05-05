<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpClient\HttpClient;
#[Route('/admin/commande')]
class CommandeController extends AbstractController
{  
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('GestCommande/commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Recaptcha3Validator $recaptcha3Validator): Response
    { // $score = null;
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();
          //  $score = $recaptcha3Validator->getLastResponse()->getScore();
            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
           
        }
        
        return $this->renderForm('GestCommande/commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
            
        ]);
    
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('GestCommande/commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('GestCommande/commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_commande_delete')]
    public function delete($id, CommandeRepository $repository,EntityManagerInterface $entityManager)
        {
            $commande = $repository->find($id);
    
            if (!$commande) {
                throw $this->createNotFoundException('Auteur non trouvé');
            }
    
            // Utilisez l'EntityManager (via $entityManager) pour supprimer l'entité
            $entityManager->remove($commande);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_commande_index');
        }  
     
        #[Route('/generate-qr-code/{id}', name: 'generate_qr_code')]
        public function generateQrCode(CommandeRepository $repository, $id): Response
        {
            $commande = $repository->find($id);

    // Créez le contenu du QR code
    $qrContent = sprintf(
        "id: %s\ncontenue: %s\nmontantTotale: %sDT\nstatus: %s",
        $commande->getId(),
        $commande->getContenue(),
        $commande->getMontantTotale(),
        $commande->getStatus(),
    );

    // Générer le QR code en tant qu'image PNG
    $qrCode = $this->generateQrCodeImage($qrContent);

    // Générer une réponse avec le contenu de l'image du QR code
    $response = new Response($qrCode, Response::HTTP_OK, [
        'Content-Type' => 'image/png',
        'Content-Disposition' => 'inline; filename="qr_code.png"'
    ]);

    return $response;
        }
    
        private function generateQrCodeImage($qrContent)
        {
            
            $baseUrl = 'https://api.qrserver.com/v1/create-qr-code/';
        
            
            $params = [
                'size' => '300x300',  // Taille de l'image
                'data' => urlencode($qrContent),  // Contenu du QR code
            ];
        
           
            $url = $baseUrl . '?' . http_build_query($params);
        
            // Utiliser HttpClient pour récupérer l'image du QR code
            $client = HttpClient::create();
            $response = $client->request('GET', $url);
        
            // Récupérer le contenu de l'image
            $qrCodeData = $response->getContent();
        
            return $qrCodeData;
        }
     


        #[Route('/tri', name: 'commandes_trie')]
        public function trie(Request $request): Response
        {
            // Récupérer les commandes depuis la base de données
            $commandes = $this->getDoctrine()->getRepository(Commande::class)->findAll();
    
            // Vérifier si un tri est demandé dans la requête
            $sortBy = $request->query->get('sort_by', 'id');
            $sortOrder = $request->query->get('sort_order', 'asc');
    
            // Tri des commandes en fonction des paramètres de tri
            usort($commandes, function($a, $b) use ($sortBy, $sortOrder) {
                $getterA = 'get' . ucfirst($sortBy);
                $getterB = 'get' . ucfirst($sortBy);
                $valueA = $a->$getterA();
                $valueB = $b->$getterB();
    
                // Si les valeurs sont des chaînes, utilisez strcmp pour comparer
                if (is_string($valueA) && is_string($valueB)) {
                    $result = strcmp($valueA, $valueB);
                } else {
                    // Sinon, comparez directement les valeurs
                    $result = ($valueA < $valueB) ? -1 : (($valueA > $valueB) ? 1 : 0);
                }
    
                return ($sortOrder === 'asc') ? $result : -$result;
            });
    
            // Rendu de la vue avec les commandes triées
            return $this->render('GestCommande/commande/index.html.twig', [
                'commandes' => $commandes,
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder
            ]);
        }
}
