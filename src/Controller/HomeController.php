<?php

namespace App\Controller;


use App\Repository\ForfaitRepository;
use App\Repository\CommentairesRepository;
use App\Repository\RealisationsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
   
    public function index(ForfaitRepository $forfaitsRepository, CommentairesRepository $commentsRepository, RealisationsRepository $realisationsRepository): Response
    {
        $realisations = $realisationsRepository->findAll();
        $comments = $commentsRepository->findAll();
        $forfaits = $forfaitsRepository->findAll();
        
        return $this->render('home/index.html.twig', [
            'realisations' => $realisations,
            'comments' => $comments,
            'forfaits' => $forfaits
        ]);
    }
      
}
