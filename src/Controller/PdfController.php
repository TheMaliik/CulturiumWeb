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
            
            $events = $this->getDoctrine()->getRepository(Events::class)->findAll();
            if (!$events) {
                throw $this->createNotFoundException('Aucun événement trouvé.');
            }
            $pdfContent = $this->renderView('client_museum/PDF/pdf1.1.html.twig', [
                'events' => $events,
            ]);
            $dompdf = new Dompdf();
            $dompdf->loadHtml($pdfContent);
            $dompdf->render();
            return new Response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
            ]);
        }
        #[Route('/pdf1.2', name: 'app_pdf1')]
        public function generatePdf1(): Response
        {
            $events = $this->getDoctrine()->getRepository(Events::class)->findAll();
            if (!$events) {
                throw $this->createNotFoundException('Aucun événement trouvé.');
            }
            $pdfContent = $this->renderView('client_museum/PDF/pdf1.2.html.twig', [
                'events' => $events,
            ]);
            $dompdf = new Dompdf();
            $dompdf->loadHtml($pdfContent);
            $dompdf->render();
            return new Response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
            ]);
        }
        #[Route('/pdf1.3', name: 'app_pdf1.3')]
        public function generatePdf2(): Response
        {
            $events = $this->getDoctrine()->getRepository(Events::class)->findAll();
            if (!$events) {
                throw $this->createNotFoundException('Aucun événement trouvé.');
            }
            $pdfContent = $this->renderView('client_museum/PDF/pdf1.3.html.twig', [
                'events' => $events,
            ]);
            $dompdf = new Dompdf();
            $dompdf->loadHtml($pdfContent);
            $dompdf->render();
            return new Response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
            ]);
        }
        #[Route('/pdf2.1', name: 'app_pdf2.1')]
        public function generatePdf3(): Response
        {
            $events = $this->getDoctrine()->getRepository(Events::class)->findAll();
            if (!$events) {
                throw $this->createNotFoundException('Aucun événement trouvé.');
            }
            $pdfContent = $this->renderView('client_museum/PDF/pdf2.1.html.twig', [
                'events' => $events,
            ]);
            $dompdf = new Dompdf();
            $dompdf->loadHtml($pdfContent);
            $dompdf->render();
            return new Response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
            ]);
        }
        #[Route('/pdf2.2', name: 'app_pdf2.2')]
        public function generatePdf4(): Response
        {
            $events = $this->getDoctrine()->getRepository(Events::class)->findAll();
            if (!$events) {
                throw $this->createNotFoundException('Aucun événement trouvé.');
            }
            $pdfContent = $this->renderView('client_museum/PDF/pdf2.2.html.twig', [
                'events' => $events,
            ]);
            $dompdf = new Dompdf();
            $dompdf->loadHtml($pdfContent);
            $dompdf->render();
            return new Response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
            ]);
        }
        #[Route('/pdf2.3', name: 'app_pdf2.3')]
        public function generatePdf5(): Response
        {
            $events = $this->getDoctrine()->getRepository(Events::class)->findAll();
            if (!$events) {
                throw $this->createNotFoundException('Aucun événement trouvé.');
            }
            $pdfContent = $this->renderView('client_museum/PDF/pdf2.3.html.twig', [
                'events' => $events,
            ]);
            $dompdf = new Dompdf();
            $dompdf->loadHtml($pdfContent);
            $dompdf->render();
            return new Response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
            ]);
        }
}