<?php

namespace App\Controller;

use App\Repository\ImagesRepository;
use App\Repository\RealisationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RealisationsController extends AbstractController
{
    #[Route('/realisations', name: 'realisations')]
    public function index(RealisationsRepository $realisationsRepository): Response
    {
        $realisations = $realisationsRepository->findAll();
        
        return $this->render('realisations/index.html.twig', [
            'realisations' => $realisations,
        ]);
    }


    #[Route('/realisations/photos/{id}', name: 'photo_all')]
    public function allphoto(RealisationsRepository $realisationsRepository, int $id, ImagesRepository $imagesRepository): Response
    {
        $realisation = $realisationsRepository->find($id);
        $images = $realisation->getImages();
        
        return $this->render('realisations/photoall.html.twig', [
            'realisation' => $realisation,
            'images' => $images
        ]);
    }

}
