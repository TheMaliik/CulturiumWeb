<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminStatsUserStatutController extends AbstractController
{
    #[Route('/admin/stats/user/statut', name: 'app_admin_stats_user_statut')]
    public function stats(UserRepository $userRepository): Response
    {
        // Récupérer le nombre total d'utilisateurs
        $totalUsers = $userRepository->countAllUsers();

        // Récupérer le nombre d'utilisateurs approuvés
        $approvedUsers = $userRepository->countApprovedUsers();

        // Récupérer le nombre d'utilisateurs bloqués
        $blockedUsers = $userRepository->countBlockedUsers();

        // Passer les données au template pour affichage
        return $this->render('admin_stats_user_statut/stats.html.twig', [
            'totalUsers' => $totalUsers,
            'approvedUsers' => $approvedUsers,
            'blockedUsers' => $blockedUsers,
        ]);
    }
}
