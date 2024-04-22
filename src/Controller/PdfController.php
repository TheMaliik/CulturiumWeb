<?php

    namespace App\Controller;

    use App\Entity\Events;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Dompdf\Dompdf;
    
    #[Route('/pdf', name: 'app_pdf')]
    class PdfController extends AbstractController
    {
        public function generatePdf(): Response
        {
            // Récupérer les données des événements depuis votre source de données
            $events = $this->getDoctrine()->getRepository(Events::class)->findAll();
    
            // Vérifier si les données des événements existent
            if (!$events) {
                throw $this->createNotFoundException('Aucun événement trouvé.');
            }
    
            // Générer le contenu PDF
            $pdfContent = $this->renderView('client_museum/PDF/pdf1.1.html.twig', [
                'events' => $events,
            ]);
    
            // Créer une instance de Dompdf
            $dompdf = new Dompdf();
    
            // Charger le contenu HTML dans Dompdf
            $dompdf->loadHtml($pdfContent);
    
            // Rendre le PDF
            $dompdf->render();
    
            // Retourner le PDF en tant que réponse HTTP
            return new Response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
            ]);
        }

}