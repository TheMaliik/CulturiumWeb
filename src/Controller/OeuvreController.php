<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Oeuvre;
use App\Form\OeuvreType;
use App\Repository\OeuvreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Repository\CategorieRepository;
use Endroid\QrCode\QrCode;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Historique;




#[Route('/oeuvre')]
class OeuvreController extends AbstractController
{

    
    #[Route('/', name: 'app_oeuvre_index', methods: ['GET'])]
    public function index(OeuvreRepository $oeuvreRepository, CategorieRepository $categorieRepository, PaginatorInterface $paginator, Request $request): Response
    {
        
        $queryBuilder = $oeuvreRepository->createQueryBuilder('o');
    
        $pagination = $paginator->paginate(
            $queryBuilder, // Utilisez le QueryBuilder directement sans appeler getQuery()
            $request->query->getInt('page', 1), // La page actuelle, 1 par défaut
            2 // Nombre d'éléments par page
        );
    
        return $this->render('oeuvre/index.html.twig', [
            'pagination' => $pagination, // Passer la pagination pour l'affichage des oeuvres
            'categories' => $categorieRepository->findAll() ,// Passer les catégories pour un autre usage dans le template
            'oeuvres' => $oeuvreRepository->findAll() // Passer les catégories pour un autre usage dans le template
        ]);
    }
  

   

#[Route('/ajax-search', name: 'app_oeuvre_ajax_search', methods: ['POST'])]
public function ajaxSearch(Request $request, OeuvreRepository $oeuvreRepository): JsonResponse
{
    $searchTerm = json_decode($request->getContent(), true)['q'] ?? '';

    $oeuvres = [];

    if ($searchTerm) {
        $oeuvres = $oeuvreRepository->searchByTerm($searchTerm);
    }

    // Render the search results partial view as a string
    $html = $this->renderView('oeuvre/_search_results.html.twig', [
        'oeuvres' => $oeuvres,
    ]);

    return new JsonResponse(['html' => $html]);
}

    
    #[Route('/s', name: 'app_oeuvre_index_s', methods: ['GET'])]
    public function indexs(Request $request,PaginatorInterface $paginator,OeuvreRepository $oeuvreRepository,CategorieRepository $categorieRepository): Response
    {
        $queryBuilder = $oeuvreRepository->createQueryBuilder('o');
    
        $pagination = $paginator->paginate(
            $queryBuilder, // Utilisez le QueryBuilder directement sans appeler getQuery()
            $request->query->getInt('page', 1), // La page actuelle, 1 par défaut
            2 // Nombre d'éléments par page
        );
        $searchTerm = $request->query->get('q');
    
        // Debugging: Dump the search term
        dump($searchTerm);
        
        $oeuvres = [];
        
        if ($searchTerm) {
            $oeuvres = $oeuvreRepository->searchByTerm($searchTerm);
        }
    
        return $this->render('oeuvre/index.html.twig', [
            'oeuvres' => $oeuvres,
            'searchTerm' => $searchTerm,
            'categories' => $categorieRepository->findAll(),
            'pagination' => $pagination, // Passer la pagination pour l'affichage des oeuvres

        ]);
    }

    #[Route('/admin', name: 'app_oeuvre_indexadmin', methods: ['GET'])]
    public function indexadmin(OeuvreRepository $oeuvreRepository): Response
    {
        return $this->render('oeuvre/indexadmin.html.twig', [
            'oeuvres' => $oeuvreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_oeuvre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $oeuvre = new Oeuvre();
        $form = $this->createForm(OeuvreType::class, $oeuvre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                // Generate a unique filename
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload exception
                    $this->addFlash('error', 'An error occurred while uploading the image.');
                    return $this->redirectToRoute('app_produit_new');
                }
    
                // Set the image property in the entity to the relative path of the uploaded file
                $oeuvre->setImage('images/'.$newFilename);
            }



            $entityManager->persist($oeuvre);
            $entityManager->flush();
             // After persisting the oeuvre, create a Historique entity
             $historique = new Historique();
             $historique->setEtat('ART PIECE ADDED');
             $historique->setDate(new \DateTime());

            // Persist the Historique entity
             $entityManager->persist($historique);
             $entityManager->flush();
             $this->addFlash('success', 'Your Exposition has been added successfully.');

            return $this->redirectToRoute('app_oeuvre_indexadmin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('oeuvre/new.html.twig', [
            'oeuvre' => $oeuvre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_oeuvre_show', methods: ['GET'])]
    public function show(Oeuvre $oeuvre): Response
    {
        return $this->render('oeuvre/show.html.twig', [
            'oeuvre' => $oeuvre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_oeuvre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Oeuvre $oeuvre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OeuvreType::class, $oeuvre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

 $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                // Generate a unique filename
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload exception
                    $this->addFlash('error', 'An error occurred while uploading the image.');
                    return $this->redirectToRoute('app_oeuvre_new');
                }
    
                // Set the image property in the entity to the relative path of the uploaded file
                $oeuvre->setImage('images/'.$newFilename);
            }



            $entityManager->flush();
             // Create a Historique entity for the update action
             $historique = new Historique();
             $historique->setEtat('ART PIECE UPDATED');
             $historique->setDate(new \DateTime());
     
             // Persist the Historique entity
             $entityManager->persist($historique);
             $entityManager->flush();
             $this->addFlash('success', 'Your art piece have been updated successfully.');

            return $this->redirectToRoute('app_oeuvre_indexadmin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('oeuvre/edit.html.twig', [
            'oeuvre' => $oeuvre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_oeuvre_delete', methods: ['POST'])]
    public function delete(Request $request, Oeuvre $oeuvre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$oeuvre->getId(), $request->request->get('_token'))) {
              // Create a Historique entity for the deletion action
              $historique = new Historique();
              $historique->setEtat('ART PIECE DELETED');
              $historique->setDate(new \DateTime());
      
              // Persist the Historique entity
              $entityManager->persist($historique);
            $entityManager->remove($oeuvre);
            $entityManager->flush();
            $this->addFlash('success', 'Your art piece have been deleted successfully.');
        }

        return $this->redirectToRoute('app_oeuvre_indexadmin', [], Response::HTTP_SEE_OTHER);
    }

    // Controller method to handle AJAX request for filtered oeuvres
    #[Route('/filter-oeuvres', name: 'app_filter_oeuvres', methods: ['GET'])]
    public function filterOeuvres(Request $request, OeuvreRepository $oeuvreRepository): JsonResponse
    {
        $categoryId = $request->query->get('categoryId');
        $oeuvres = $oeuvreRepository->findBy(['typeOeuvre' => $categoryId]);
    
        $data = [];
        foreach ($oeuvres as $oeuvre) {
            $data[] = [
                'id' => $oeuvre->getId(),
                'nomArtiste' => $oeuvre->getNomArtiste(),
                'image' => $oeuvre->getImage(),
                // Add other fields as needed
            ];
        }
    
        return new JsonResponse($data);

        
    }
    



   

}
