<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('GestForum/post/index.html.twig', [
            'blogPosts' => $postRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('GestForum/post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('GestForum/post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('GestForum/post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_post_index', [
        ], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}', name: 'app_preview_delete', methods: ['POST'])]
    public function preview(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
      
        return $this->redirectToRoute('app_post_index', [
        ], Response::HTTP_SEE_OTHER);
    }
    #[Route('/posts/sort/asc', name: 'app_posts_sort_asc')]
    public function sortAsc(): Response
    {
        // Logic to sort posts by ASC order
        // For example:
        // $posts = $this->getDoctrine()->getRepository(Post::class)->findBy([], ['createdAt' => 'ASC']);

        // Return a response, render a template, or redirect as needed
        return $this->redirectToRoute('app_post_index'); // Redirect to the post index page
    }

    #[Route('/posts/sort/{order}', name: 'app_posts_sort')]
    public function sortPosts(Request $request, PostRepository $postRepository): Response
    {
        $order = $request->attributes->get('order');
    
        // Check if the order is valid
        if ($order === 'asc' || $order === 'desc') {
            // Retrieve posts from the repository and sort them based on the order
            $posts = $postRepository->findBy([], ['id' => $order === 'asc' ? 'ASC' : 'DESC']);
    
            // Render the template with the sorted posts
            return $this->render('GestForum/post/index.html.twig', [
                'blogPosts' => $posts,
            ]);
        }
    
        // If the order is not valid, redirect to the post index page
        return $this->redirectToRoute('app_post_index');
    }
    #[Route('/posts/search', name: 'app_posts_search', methods: ['GET'])]
    public function search(Request $request, PostRepository $postRepository): Response
    {
        $searchQuery = $request->query->get('search');
    
        // Perform search query using repository method
        if (is_numeric($searchQuery)) {
            // Search by post ID
            $posts = $postRepository->findBy(['id' => $searchQuery]);
        } else {
            // Search by title
            $posts = $postRepository->findBy(['titre' => $searchQuery]);
        }
    
        if ($posts) {
            // If posts are found, render the template with the selected posts
            return $this->render('GestForum/post/index.html.twig', [
                'blogPosts' => $posts,
                'searchQuery' => $searchQuery,
            ]);
        } else {
            // If no posts are found, set a flash message and redirect back to the index page
            $this->addFlash('error', 'No posts found with the search : "' . $searchQuery . '".');
            return $this->redirectToRoute('app_post_index');
        }}
      
    }
