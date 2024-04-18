<?php

namespace App\Controller;

use App\Form\AdresseSearchType;

use App\Entity\Adresse;
use App\Form\AdresseType;
use App\Repository\AdresseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/adresse')]
class AdresseController extends AbstractController


{
    #[Route('/', name: 'app_adresse_index', methods: ['GET', 'POST'])]
    public function index(Request $request, AdresseRepository $adresseRepository): Response
    {
        $form = $this->createForm(AdresseSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchTerm = $form->get('search')->getData();
            $adresses = $adresseRepository->findBySearchTerm($searchTerm);
        } else {
            $adresses = $adresseRepository->findAll();
        }

        return $this->render('adresse/index.html.twig', [
            'form' => $form->createView(),
            'adresses' => $adresses,
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

    #[Route('/{id}', name: 'app_adresse_delete', methods: ['POST'])]
    public function delete(Request $request, Adresse $adresse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adresse->getId(), $request->request->get('_token'))) {
            $entityManager->remove($adresse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_adresse_index', [], Response::HTTP_SEE_OTHER);
    }

  
  
    
}
