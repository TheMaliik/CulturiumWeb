<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\ComRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/commentaire')]
class CommentaireController extends AbstractController
{
    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(ComRepository $comRepository): Response
    {
        return $this->render('GestForum/commentaire/index.html.twig', [
            'blogComs' => $comRepository->findAll(),
        ]);
        
     }

    #[Route('/new', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('GestForum/commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('GestForum/commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('GestForum/commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/com/sort/{order}', name: 'app_coms_sort')]
    public function sortcoms(Request $request, ComRepository $comRepository): Response
    {
        $order = $request->attributes->get('order');
    
        // Check if the order is valid
        if ($order === 'asc' || $order === 'desc') {
            // Retrieve comments from the repository and sort them based on the order
            $comments = $comRepository->findBy([], ['id' => $order === 'asc' ? 'ASC' : 'DESC']);
    
            // Render the template with the sorted comments
            return $this->render('GestForum/commentaire/index.html.twig', [
                'blogComs' => $comments,
            ]);
        }
    
        // If the order is not valid, redirect to the comment index page
        return $this->redirectToRoute('app_commentaire_search');
    }
    #[Route('/com/search', name: 'app_coms_search', methods: ['GET'])]
public function search(Request $request, ComRepository $comRepository): Response
{
    $searchQuery = $request->query->get('search');

    // Perform search query using repository method
    if (is_numeric($searchQuery)) {
        // Search by post ID
        $coms = $comRepository->findBy(['id' => $searchQuery]);
    } else {
        // Search by title
        $coms = $comRepository->findBy(['descreption' => $searchQuery]);
    }

    if ($coms) {
        // If posts are found, render the template with the selected posts
        return $this->render('GestForum/commentaire/index.html.twig', [
            'blogComs' => $coms,
            'searchQuery' => $searchQuery,
        ]);
    } else {
        // If no posts are found, set a flash message and redirect back to the index page
        $this->addFlash('error', 'No coms found with the search : "' . $searchQuery . '".');
        return $this->redirectToRoute('app_commentaire_index');
    }} 
}