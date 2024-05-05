<?php

namespace App\Controller;

use App\Repository\MuseumRepository;
use App\Entity\Museum;
use App\Form\Museum1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;



#[Route('/admin/museum')]
class AdminMuseumController extends AbstractController
{
        
    #[Route('/', name: 'app_admin_museum_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager, MuseumRepository $museumRepository): Response
    {
        $searchTerm = $request->query->get('search');

        if ($searchTerm) {
            $museums = $museumRepository->findByName($searchTerm);
        } else {
            $museums = $museumRepository->findAll();
        }

        return $this->render('admin_museum/index.html.twig', [
            'museums' => $museums,
        ]);
        
    }


    #[Route('/admin/museum/new', name: 'app_admin_museum_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $museum = new Museum();
        $form = $this->createForm(Museum1Type::class, $museum);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $recaptchaResponse = $request->request->get('g-recaptcha-response');
            $isRecaptchaValid = $this->isRecaptchaValid($recaptchaResponse);

            if (!$isRecaptchaValid) {
                $this->addFlash('error', 'reCAPTCHA validation failed. Please try again.');
                return $this->redirectToRoute('app_admin_museum_new');
            }
            // Vérifiez si le nom du musée est déjà utilisé
            $existingMuseum = $entityManager->getRepository(Museum::class)->findOneBy(['name' => $museum->getName()]);
            
            if ($existingMuseum) {
                // Ajoutez un message d'erreur au champ name du formulaire
                $form->get('name')->addError(new FormError('Ce nom de musée est déjà utilisé.'));
                // Rendez à nouveau le formulaire avec le message d'erreur
                return $this->renderForm('admin_museum/new.html.twig', [
                    'museum' => $museum,
                    'form' => $form,
                ]);     
            }
            
            $entityManager->persist($museum);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_admin_museum_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('admin_museum/new.html.twig', [
            'museum' => $museum,
            'form' => $form,
        ]);
    }
    

    #[Route('/{idm}', name: 'app_admin_museum_show', methods: ['GET'])]
    public function show(Museum $museum): Response
    {
        return $this->render('admin_museum/show.html.twig', [
            'museum' => $museum,
        ]);
    }

    #[Route('/{idm}/edit', name: 'app_admin_museum_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Museum $museum, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Museum1Type::class, $museum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_museum_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_museum/edit.html.twig', [
            'museum' => $museum,
            'form' => $form,
        ]);
    }

    #[Route('/{idm}', name: 'app_admin_museum_delete', methods: ['POST'])]
    public function delete(Request $request, Museum $museum, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$museum->getIdm(), $request->request->get('_token'))) {
            $entityManager->remove($museum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_museum_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/admin/museum/sort', name: 'app_admin_museum_sort', methods: ['GET'])]
public function sort(Request $request, MuseumRepository $museumRepository): Response
{
    $criteria = $request->query->get('criteria', 'name');
    $direction = $request->query->get('direction', 'asc');

    $museums = $museumRepository->findBy([], [$criteria => $direction]);

    return $this->render('admin_museum/index.html.twig', [
        'museums' => $museums,
    ]);
}
private function isRecaptchaValid($recaptchaResponse)
    {
        $secretKey = '6LdgqIgpAAAAAIL7zt1gZh87stU7vGsgR3Yl7h7X';
        $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $postData = http_build_query([
            'secret' => $secretKey,
            'response' => $recaptchaResponse
        ]);

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postData
            ]
        ]);

        $response = file_get_contents($recaptchaUrl, false, $context);
        $result = json_decode($response);

        return $result->success;
    }
}


    

