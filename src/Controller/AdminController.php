<?php

namespace App\Controller;

use App\Entity\Forfait;
use App\Form\ForfaitType;
use App\Repository\ForfaitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/forfaits', name: 'admin_forfaits_index')]
   
    public function adminIndex(ForfaitRepository $forfaitsRepository): Response
    {
        $forfaits = $forfaitsRepository->findAll();
        
        return $this->render('admin/forfaits.html.twig', [
            'forfaits' => $forfaits,
        ]);
    }

    #[Route('/admin/forfaits/create', name: 'forfaits_create')]
    public function create(ForfaitRepository $forfaitsRepository, Request $request,  ManagerRegistry $managerRegistry)
    {
        $forfaits = $forfaitsRepository->findAll();
        
        if (count($forfaits) >= 3){
            $this->addFlash('danger', 'Il ne peut pas avoir plus de trois forfaits veuillez modifier un existant');
            return $this->redirectToRoute('admin_forfaits_index');
        } else {
            $forfait = new Forfait();
            $form = $this->createForm(ForfaitType::class, $forfait); 
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $manager = $managerRegistry->getManager();
                $manager->persist($forfait);
                $manager->flush();

                $this->addFlash('success', 'Le forfait a bien été ajoutée');
                return $this->redirectToRoute('admin_forfaits_index');
            }
                return $this->render('admin/forfaitForm.html.twig', [
                'forfaitForm' => $form->createView()
            ]);
        }
    }

    #[Route('/admin/forfaits/delete/{id}', name: 'forfait_delete')]
    public function delete(Forfait $forfait, ManagerRegistry $managerRegistry): Response
    {
        $manager = $managerRegistry->getManager();

        
        // suppression de l'objet en bdd
        $manager->remove($forfait);
        $manager->flush();

        // redirection
        $this->addFlash('danger', 'Le forfait a bien été supprimé');
        return $this->redirectToRoute('admin_forfaits_index');
    }

    
    #[Route('/admin/forfait/update/{id}', name: 'forfait_update')]
    public function update(ForfaitRepository $forfaitsRepository, int $id, Request $request, ManagerRegistry $managerRegistry)
    {
        $forfait = $forfaitsRepository->find($id);
        $form = $this->createForm(ForfaitType::class, $forfait);
        $form->handleRequest($request);

        $manager = $managerRegistry->getManager();
        $manager->persist($forfait);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->flush();

            $this->addFlash('success', 'Le forfait a bien été modifiée');
            return $this->redirectToRoute('admin_forfaits_index');

        }
        
        return $this->render('admin/forfaitForm.html.twig', [
            'forfaitForm' => $form->createView()
        ]);
    }

}
