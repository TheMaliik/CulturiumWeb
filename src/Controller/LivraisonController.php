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

#[Route('/livraison')]
class LivraisonController extends AbstractController
{
    #[Route('/', name: 'livraison_index', methods: ['GET', 'POST'])]
    public function index(Request $request, LivraisonRepository $livraisonRepository): Response
    {
        $form = $this->createForm(LivraisonSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchTerm = $form->get('search')->getData();
            $livraisons = $livraisonRepository->findBySearchTerm($searchTerm);
        } else {
            $livraisons = $livraisonRepository->findAll();
        }

        return $this->render('livraison/index.html.twig', [
            'form' => $form->createView(),
            'livraisons' => $livraisons,
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

}
    

   