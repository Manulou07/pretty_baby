<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Realisations;
use App\Form\RealisationsType;
use App\Repository\ImagesRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\RealisationsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/admin/realisations', name: 'admin_realisations_index')]
    public function adminIndex(RealisationsRepository $realisationsRepository): Response
    {
        $realisations = $realisationsRepository->findAll();
        
        return $this->render('admin/realisations.html.twig', [
            'realisations' => $realisations,
        ]);
    }

    #[Route('/admin/realisations/create', name: 'rea_create')]
    public function create(Request $request,  ManagerRegistry $managerRegistry)
    {
        $realisation = new Realisations();
        $form = $this->createForm(RealisationsType::class, $realisation); 
        $form->handleRequest($request);
      

        if ($form->isSubmitted() && $form->isValid()) {

            $manager = $managerRegistry->getManager();

            $infoImg = $form['img']->getData(); 
            $extensionImg = $infoImg->guessExtension(); 
            $nomImg = time() . '-1.' . $extensionImg;
            $infoImg->move($this->getParameter('dossier_photos_realisations'), $nomImg);
            $realisation->setImg($nomImg);
            $manager->persist($realisation);

            for ($i = 2; $i <= 10; $i++) {
                if (!empty($form['img' . $i]->getData())) {
                    $image = new Images();
                    $infoImg = $form['img' . $i]->getData(); 
                    $extensionImg = $infoImg->guessExtension(); 
                    $nomImg = time() . '-' . $i . '.' . $extensionImg; 
                    $infoImg->move($this->getParameter('dossier_photos_realisations'), $nomImg);
                    $image->setNameimg($nomImg);
                    $image->setRelation($realisation);
                    $manager->persist($image);
                }
            }
            
            
            $manager->flush();

            $this->addFlash('success', 'Le contenu a bien été ajoutée');
            return $this->redirectToRoute('admin_realisations_index');
        }
       

        return $this->render('admin/realisationForm.html.twig', [
            'realisationForm' => $form->createView()
        ]);
    }

    #[Route('/admin/realisation/delete/{id}', name: 'rea_delete')]
    public function delete(Realisations $realisation, ManagerRegistry $managerRegistry): Response
    {
        $manager = $managerRegistry->getManager();

        // récupération et suppression des images secondaires
        $images = $realisation->getImages();
        foreach ($images as $img) {
            $pathImgSecondaire = $this->getParameter('dossier_photos_realisations') . '/' . $img->getNameimg();
            if (file_exists($pathImgSecondaire)) {
                unlink($pathImgSecondaire);
            }
            $manager->remove($img);
        }

        // récupération et suppression de l'image principale
        $pathImgPrincipale =  $this->getParameter('dossier_photos_realisations') . '/' . $realisation->getImg();
        if (file_exists($pathImgPrincipale)) {
            unlink($pathImgPrincipale);
        }

        // suppression de l'objet realistion en bdd
        $manager->remove($realisation);
        $manager->flush();

        // redirection
        $this->addFlash('success', 'Le contenu a bien été ajoutée');
        return $this->redirectToRoute('admin_realisations_index');
    }
}
