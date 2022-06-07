<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Realisations;
use App\Form\RealisationsType;
use App\Security\EmailVerifier;
use Symfony\Component\Mime\Address;
use App\Repository\ImagesRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\RealisationsRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RealisationsController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }
    
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
         
            if (!empty($form['img']->getData())) {
                $infoImg = $form['img']->getData(); 
                $extensionImg = $infoImg->guessExtension(); 
                $nomImg = time() . '-1.' . $extensionImg;
                $infoImg->move($this->getParameter('dossier_photos_realisations'), $nomImg);
                $realisation->setImg($nomImg);
                $manager->persist($realisation);
           }else{
            $this->addFlash('danger', 'La photo principale est obligatoire');
            return $this->redirectToRoute('rea_create');
            }

            for ($i = 2; $i <= 9; $i++) {
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
            $user = $realisation->getfkIdReservations()->getFkIdUser();

            $this->emailVerifier->sendEmailComments('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('desousa.emmanuel@gmail.com', 'Votre avis sur Pretty Baby'))
                ->to($user->getEmail())
                ->subject('Votre avis compte')
                ->htmlTemplate('realisations/avis_email.html.twig')
                               
        );
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

        // suppression de l'objet realisation en bdd
        $manager->remove($realisation);
        $manager->flush();

        // redirection
        $this->addFlash('danger', 'Le contenu a bien été supprimé');
        return $this->redirectToRoute('admin_realisations_index');
    }

    #[Route('/admin/realisation/update/{id}', name: 'rea_update')]
    public function update(RealisationsRepository $realisationsRepository, int $id, Request $request, ManagerRegistry $managerRegistry)
    {
        $realisation = $realisationsRepository->find($id);
        $form = $this->createForm(RealisationsType::class, $realisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $managerRegistry->getManager();
            $infoImg = $form['img']->getData();
            $nomOldImg = $realisation->getImg();
            
            if ($infoImg !== null) {
                $cheminImg = $this->getParameter('dossier_photos_realisations') . '/' . $nomOldImg;
                if (file_exists($cheminImg)) {
                    unlink($cheminImg);
                }
                
                $extensionImg = $infoImg->guessExtension();
                $nomImg = time() . '-1.' . $extensionImg;
                $infoImg->move($this->getParameter('dossier_photos_realisations'), $nomImg);
                $realisation->setImg($nomImg);
                $manager->persist($realisation);
            } else {
                $realisation->setImg($nomOldImg);
            }

            for ($i = 2; $i <= 9; $i++){
                $image = new Images();
                $infoImg = $form['img' . $i]->getData();
                $nomOldImg = $image->getNameimg();
                
                if ($infoImg !== null) {
                    if ($nomOldImg !== null) {
                         $cheminOldImg = $this->getParameter('dossier_photos_realisations') . '/' . $nomOldImg;
                        if (file_exists($cheminOldImg)) {
                            unlink($cheminOldImg);
                        }
                    }
                    $extensionImg = $infoImg->guessExtension();
                    $nomImg = time() . '-'.$i.'.' . $extensionImg;
                    $infoImg->move($this->getParameter('dossier_photos_realisations'), $nomImg);
                    $image->setNameimg($nomImg);
                    $image->setRelation($realisation);
                    $manager->persist($image);
                } else {
                    $image->setNameimg($nomOldImg);
                }
            }   
            
            $manager->flush();
            $this->addFlash('success', 'Le contenu a bien été modifiée');
            return $this->redirectToRoute('admin_realisations_index');
        }
            
        return $this->render('admin/realisationForm.html.twig', [
            'realisationForm' => $form->createView()
        ]);
    }

}
