<?php

namespace App\Controller;

use App\Repository\ComRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commentaire')]
class ChartController extends AbstractController
{
    #[Route('/chart', name: 'app_com_chart')]
    public function showChart(ComRepository $comRepository): Response
    {
        // Fetch the comments data grouped by date
        $commentsByDate = $comRepository->countByDate();

        // Separate the dates and comment counts for Chart.js
        $dates = [];
        $commentCounts = [];

        foreach ($commentsByDate as $row) {
            $dates[] = $row['commentDate']->format('Y-m-d'); // Format the date as needed
            $commentCounts[] = $row['count'];
        }

        return $this->render('admin/stats.html.twig', [
            'dates' => json_encode($dates),
            'commentCounts' => json_encode($commentCounts),
        ]);
    }
}
