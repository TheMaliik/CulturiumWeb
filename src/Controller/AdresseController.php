<?php

namespace App\Controller;

use App\Form\AdresseSearchType;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Adresse;
use App\Form\AdresseType;
use App\Repository\AdresseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

#[Route('/adresse')]
class AdresseController extends AbstractController



{
    private $adresseRepository;

    public function __construct(AdresseRepository $adresseRepository)
    {
        $this->adresseRepository = $adresseRepository;
    }
    #[Route('/', name: 'app_adresse_index', methods: ['GET', 'POST'])]
    public function index(Request $request, AdresseRepository $adresseRepository): Response
    {
        $form = $this->createForm(AdresseSearchType::class);
        $form->handleRequest($request);
        $sortOrder = $request->query->get('sortOrder', 'ASC'); // Récupérez l'ordre de tri depuis l'URL, avec ASC comme valeur par défaut
    
        // Définissez le champ de tri par défaut
        $sortBy = $request->query->get('sortBy', 'id');
        $orderBy = [$sortBy => $sortOrder];
    
        if ($form->isSubmitted() && $form->isValid()) {
            $searchTerm = $form->get('search')->getData();
            $adresses = $adresseRepository->findBySearchTerm($searchTerm, $orderBy);
        } else {
            $adresses = $adresseRepository->findBy([], [$sortBy => $sortOrder]); // Tri des adresses en fonction des paramètres de tri
        }
    
        return $this->render('adresse/index.html.twig', [
            'form' => $form->createView(),
            'adresses' => $adresses,
            'sortOrder' => $sortOrder === 'ASC' ? 'DESC' : 'ASC', // Inversez l'ordre de tri pour les prochains clics sur les liens de tri
        ]);
    }

    #[Route('/search', name: 'app_adresse_search', methods: ['GET'])]
    public function search(Request $request, AdresseRepository $adresseRepository): Response
    {
        $searchTerm = $request->query->get('searchTerm');
        if ($searchTerm) {
            $adresses = $adresseRepository->findBySearchTerm($searchTerm);
        } else {
            // Si le champ de recherche est vide, renvoyer toutes les adresses
            $adresses = $adresseRepository->findAll();
        }
    
        return $this->render('adresse/_adresses.html.twig', [
            'adresses' => $adresses,
        ]);
    }
    
    



    #[Route('/new', name: 'app_adresse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adresse = new Adresse();
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adresse);
            $entityManager->flush();

            return $this->redirectToRoute('app_adresse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adresse/new.html.twig', [
            'adresse' => $adresse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adresse_show', methods: ['GET'])]
    public function show(Adresse $adresse): Response
    {
        return $this->render('adresse/show.html.twig', [
            'adresse' => $adresse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_adresse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adresse $adresse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_adresse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adresse/edit.html.twig', [
            'adresse' => $adresse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_adresse_delete')]
    public function delete($id, AdresseRepository $repository,EntityManagerInterface $entityManager)
        {
            $adresse = $repository->find($id);
    
            if (!$adresse) {
                throw $this->createNotFoundException('Auteur non trouvé');
            }
    
            // Utilisez l'EntityManager (via $entityManager) pour supprimer l'entité
            $entityManager->remove($adresse);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_adresse_index');
        }  

    #[Route('/export/excel', name: 'adresse_export_excel', methods: ['GET'])]
    public function exportToExcel(): Response
    {
        // Start output buffering
        ob_start();
        // Récupérer les adresses depuis le repository
        $adresses = $this->adresseRepository->findAll();

        // Créer un nouvel objet Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Ajouter des en-têtes de colonnes
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('C1', 'Adresse');
        $sheet->setCellValue('D1', 'Ville');
        $sheet->setCellValue('E1', 'Code Postal');
        $row = 2;

        // Ajouter les données des adresses dans le fichier Excel
        foreach ($adresses as $adresse) {
            $sheet->setCellValue('A'.$row, $adresse->getId());
            $sheet->setCellValue('C'.$row, $adresse->getAdresse());
            $sheet->setCellValue('D'.$row, $adresse->getVille());
            $sheet->setCellValue('E'.$row, $adresse->getCodePostal());
            $row++;
        }
 // Create a Writer object for Excel
 $writer = new Xlsx($spreadsheet);

 // Save the Excel file content to the output buffer
 $writer->save('php://output');

 // Get the content from the output buffer
 $content = ob_get_clean();

 // Create an HTTP response with the Excel file content for download
 $response = new StreamedResponse(function () use ($content) {
     echo $content;
 });

 $disposition = $response->headers->makeDisposition(
     ResponseHeaderBag::DISPOSITION_ATTACHMENT,
     'adresses.xlsx'
 );
 $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
 $response->headers->set('Content-Disposition', $disposition);

 return $response;
}
  
    
    
    #[Route('/export/pdf', name: 'adresse_export_pdf', methods: ['GET'])]
    public function exportToPdf(AdresseRepository $adresseRepository): Response
    {
        // Récupérer les adresses depuis le repository
        $adresses = $adresseRepository->findAll();
    
        // Configuration de dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
    
        // Création d'une instance de Dompdf
        $dompdf = new Dompdf($options);
    
        // Génération du contenu HTML
        $html = $this->renderView('adresse/pdf.html.twig', [
            'adresses' => $adresses,
        ]);
    
        // Chargement du contenu HTML dans Dompdf
        $dompdf->loadHtml($html);
    
        // Réglages du papier et du format
        $dompdf->setPaper('A4', 'landscape');
    
        // Génération du PDF
        $dompdf->render();
    
        // Envoi du PDF en tant que réponse
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="adresses.pdf"');
    
        return $response;
    }
    
    
}
