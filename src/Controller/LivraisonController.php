<?php

namespace App\Controller;
use App\Form\LivraisonSearchType;
use App\Entity\Livraison;
use App\Form\LivraisonType;
use App\Repository\LivraisonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/livraison')]
class LivraisonController extends AbstractController

{
    
        private $livraisonRepository;
    
        public function __construct(LivraisonRepository $livraisonRepository)
        {
            $this->livraisonRepository = $livraisonRepository;
        }
    #[Route('/', name: 'livraison_index', methods: ['GET', 'POST'])]
    public function index(Request $request, LivraisonRepository $livraisonRepository): Response
    {
        $form = $this->createForm(LivraisonSearchType::class);
        $form->handleRequest($request);

        // Déterminez le paramètre de tri par date de livraison
        $sortBy = $request->query->get('sortBy', 'dateDeLivraison');
        $sortOrder = $request->query->get('sortOrder', 'ASC');
        $orderBy = [$sortBy => $sortOrder];

        if ($request->query->has('sortBy')) {
            $sortBy = $request->query->get('sortBy');
            $sortOrder = $request->query->get('sortOrder', 'ASC');
            $orderBy = [$sortBy => $sortOrder];
        }
    
        if ($form->isSubmitted() && $form->isValid()) {
            $searchTerm = $form->get('search')->getData();
            $livraisons = $livraisonRepository->findBySearchTerm($searchTerm, $orderBy);
        } else {
            // Utilisez le repository pour récupérer les livraisons triées
            $livraisons = $livraisonRepository->findBy([], $orderBy);
        }

        return $this->render('livraison/index.html.twig', [
            'form' => $form->createView(),
            'livraisons' => $livraisons,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
    }
    #[Route('/search', name: 'livraison_search', methods: ['GET'])]
public function search(Request $request, LivraisonRepository $livraisonRepository): Response
{
    $searchTerm = $request->query->get('searchTerm');
    if ($searchTerm) {
        $livraisons = $livraisonRepository->findBySearchTerm($searchTerm);
    } else {
        // Si le champ de recherche est vide, renvoyer toutes les livraisons
        $livraisons = $livraisonRepository->findAll();
    }

    return $this->render('livraison/livraisons.html.twig', [
        'livraisons' => $livraisons,
    ]);
}

#[Route('/new', name: 'livraison_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $livraison = new Livraison();
    $form = $this->createForm(LivraisonType::class, $livraison);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($livraison);
        $entityManager->flush();

        return $this->redirectToRoute('livraison_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('livraison/new.html.twig', [
        'livraison' => $livraison,
        'form' => $form,
    ]);
}

#[Route('/{id}', name: 'livraison_show', methods: ['GET'])]
public function show(Livraison $livraison): Response
{
    return $this->render('livraison/show.html.twig', [
        'livraison' => $livraison,
    ]);
}
#[Route('/{id}/edit', name: 'livraison_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Livraison $livraison, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(LivraisonType::class, $livraison);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) 
    {$entityManager->flush();
        return $this->redirectToRoute('livraison_show', ['id' => $livraison->getId()]);
    }
    return $this->renderForm('livraison/edit.html.twig', [
        'livraison' => $livraison,
        'form' => $form,
    ]);



}

#[Route('/{id}', name: 'livraison_delete', methods: ['POST'])]
public function delete(Request $request, Livraison $livraison, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete'.$livraison->getId(), $request->request->get('_token'))) {
        $entityManager->remove($livraison);
        $entityManager->flush();
    }

    return $this->redirectToRoute('livraison_index', [], Response::HTTP_SEE_OTHER);
}



#[Route('/export/excel', name: 'livraison_export_excel', methods: ['GET'])]
public function exportToExcel(): Response
{
    ob_start();
    // Récupérer les livraisons depuis le repository
    $livraisons = $this->livraisonRepository->findAll();

    // Créer un nouvel objet Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Ajouter des en-têtes de colonnes
    $sheet->setCellValue('A1', 'ID ');
    $sheet->setCellValue('B1', 'Date de Livraison');
    $sheet->setCellValue('C1', 'Statut');
    $sheet->setCellValue('D1', 'Depot');
    $sheet->setCellValue('E1', 'Adresse');
    $row = 2;

    // Ajouter les données des livraisons dans le fichier Excel
    foreach ($livraisons as $livraison) {
        $sheet->setCellValue('A'.$row, $livraison->getId());
        $sheet->setCellValue('B'.$row, $livraison->getDateDeLivraison()->format('Y-m-d'));
        $sheet->setCellValue('C'.$row, $livraison->getStatut());
        $sheet->setCellValue('D'.$row, $livraison->getDepot());
        $sheet->setCellValue('E'.$row, $livraison->getAdresse()->getAdresse());
        $row++;
    }

    // Créer un objet Writer pour Excel
    $writer = new Xlsx($spreadsheet);

      // Écrire le contenu du fichier Excel dans la réponse HTTP
      $writer->save('php://output');

    // Créer une réponse HTTP avec le fichier Excel en téléchargement
    $response = new Response();
    $disposition = $response->headers->makeDisposition(
        ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        'livraisons.xlsx'
    );
    $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $response->headers->set('Content-Disposition', $disposition);

    

    return $response;
}

#[Route('/export/pdf', name: 'livraison_export_pdf', methods: ['GET'])]
public function exportToPdf(LivraisonRepository $livraisonRepository): Response
{
    // Récupérer les livraisons depuis le repository
    $livraisons = $livraisonRepository->findAll();

    // Configuration de dompdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);

    // Création d'une instance de Dompdf
    $dompdf = new Dompdf($options);

    // Génération du contenu HTML
    $html = $this->renderView('livraison/pdf.html.twig', [
        'livraisons' => $livraisons,
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
    $response->headers->set('Content-Disposition', 'attachment; filename="livraisons.pdf"');

    return $response;
}


}
    

   