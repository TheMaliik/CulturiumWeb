<?php

namespace App\Controller;

use App\Entity\Events;
use App\Form\EventsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\StringToDateTimeTransformer;



#[Route('/admin/events')]
class AdminEventsController extends AbstractController
{
    #[Route('/', name: 'app_admin_events_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $events = $entityManager
            ->getRepository(Events::class)
            ->findAll();

        return $this->render('admin_events/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/new', name: 'app_admin_events_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $event = new Events();
    $form = $this->createForm(EventsType::class, $event);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Gérer le téléchargement et la sauvegarde de l'image
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            // Générer un nom unique pour le fichier
            $newFilename = uniqid().'.'.$imageFile->guessExtension();

            // Déplacer le fichier vers le répertoire où les images sont stockées
            try {
                $imageFile->move(
                    $this->getParameter('events_image_directory'), // Chemin vers le répertoire
                    $newFilename
                );
            } catch (FileException $e) {
                // Gérer les exceptions si le téléchargement échoue
            }

            // Mettre à jour la propriété 'image' de l'entité avec le nom du fichier
            $event->setImage($newFilename);
        }

        // Persist l'entité avec l'image mise à jour
        $entityManager->persist($event);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_events_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('admin_events/new.html.twig', [
        'event' => $event,
        'form' => $form,
    ]);
}



    #[Route('/{ide}', name: 'app_admin_events_show', methods: ['GET'])]
    public function show(Events $event): Response
    {
        return $this->render('admin_events/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{ide}/edit', name: 'app_admin_events_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Events $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventsType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_events_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_events/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{ide}', name: 'app_admin_events_delete', methods: ['POST'])]
    public function delete(Request $request, Events $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getIde(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_events_index', [], Response::HTTP_SEE_OTHER);
    }
}
