<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\Post2Type;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client')]
class AddpostController extends AbstractController
{
    #[Route('/index', name: 'app_addpost_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('GestForum/addpost/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    #[Route('/addpost', name: 'app_addpost_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
    $post = new Post();
    $form = $this->createForm(Post2Type::class, $post);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($post);
        $entityManager->flush();

        return $this->redirectToRoute('app_addpost_new', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('GestForum/addpost/new.html.twig', [
        'form' => $form->createView(), // Pass the form view to the template
    ]);
}
}
