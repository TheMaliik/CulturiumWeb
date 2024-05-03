<?php

namespace App\Controller;
use App\Form\AdresseClientType;
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
use Pagerfanta\Pagerfanta;
use Knp\Component\Pager\PaginatorInterface;



use Twilio\Rest\Client;
use App\Repository\UserRepository;


class HomeController extends AbstractController
{
    

    
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('index.htm');
    }
    
    #[Route('/adresseClient', name: 'adresseClient', methods: ['GET', 'POST'])]
    public function newClient(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adresse = new Adresse();
        $form = $this->createForm(adresseClientType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adresse);
            $entityManager->flush();

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adresseClient/adresseClient.html.twig', [
            'adresse' => $adresse,
            'form' => $form,
        ]);
    }
    
    


}
