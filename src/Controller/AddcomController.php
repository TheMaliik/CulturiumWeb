<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\AddcomType;
use App\Form\Commentaire2Type;
use App\Repository\ComRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client')]
class AddcomController extends AbstractController
{
    #[Route('/index', name: 'app_addcom_index', methods: ['GET'])]
    public function index(ComRepository $comRepository): Response
    {
        return $this->render('GestForum/addcom/index.html.twig', [
            'commentaires' => $comRepository->findAll(),
        ]);
    }

    #[Route('/addcom', name: 'app_addcom_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $commentaire = new Commentaire();

    // Set the current date
    $commentaire->setDate(new \DateTime());

    $form = $this->createForm(AddcomType::class, $commentaire);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Get the data from the form
        $data = $form->getData();
        
        // Set the description and post ID from the form
        $commentaire->setDescreption($data->getDescreption());
        $commentaire->setIdPost($data->getIdPost());

        $entityManager->persist($commentaire);
        $entityManager->flush();

        return $this->redirectToRoute('app_addcom_new', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('GestForum/addcom/new.html.twig', [
        'form' => $form->createView(),
    ]);
}}
