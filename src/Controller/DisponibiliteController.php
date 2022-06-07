<?php

namespace App\Controller;

use App\Entity\Disponibilite;
use App\Form\DisponibiliteType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\DisponibiliteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DisponibiliteController extends AbstractController
{
    #[Route('/admin/disponibilite', name: 'dispo_list')]
    public function index(DisponibiliteRepository $disponibiliteRepository, ManagerRegistry $managerRegistry): Response
    {
        // $dispo = $disponibiliteRepository->findAll();

        // $dispoPast = $disponibiliteRepository->findBy(['date_dispo' => (<= new \DateTime)]);
        $dispoPast = $disponibiliteRepository->findPastDispos();

        
        // $dispoFuture = $disponibiliteRepository->findBy(['date' > new \DateTime]);
        $dispoFuture = $disponibiliteRepository->findFutureDispos();

        return $this->render('disponibilite/index.html.twig', [
            'dispos' => $dispoFuture, 
            'dispoPast' => $dispoPast
        ]);
    }

    #[Route('/admin/disponibilite/create', name: 'dispo_create')]
    public function create(Request $request,  ManagerRegistry $managerRegistry)
    {       
            $dispo = new Disponibilite();
            $form = $this->createForm(DisponibiliteType::class, $dispo); 
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
               
                $manager = $managerRegistry->getManager();
                $manager->persist($dispo);
                $manager->flush();

                $this->addFlash('success', 'La date a été ajoutée, recommencez si vous souhaitez une autre date, sinon cliquer sur terminer');
                return $this->redirectToRoute('dispo_create');
                }
                
                return $this->render('disponibilite/dispoForm.html.twig', [
                'dispoForm' => $form->createView()
            ]);
    }

    #[Route('/admin/disponibilite/update/{id}', name: 'dispo_update')]
    public function update(DisponibiliteRepository $disponibiliteRepository, int $id, Request $request, ManagerRegistry $managerRegistry)
    {
        $dispo = $disponibiliteRepository->find($id);
        $form = $this->createForm(DisponibiliteType::class, $dispo);
        $form->handleRequest($request);

        $manager = $managerRegistry->getManager();
        $manager->persist($dispo);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->flush();

            $this->addFlash('success', 'La date a bien été modifiée');
            return $this->redirectToRoute('dispo_list');

        }
        
        return $this->render('disponibilite/dispoForm.html.twig', [
            'dispoForm' => $form->createView()
        ]);
    }

    #[Route('/admin/disponibilite/delete/{id}', name: 'dispo_delete')]
    public function delete(Disponibilite $dispo, ManagerRegistry $managerRegistry): Response
    {
        $manager = $managerRegistry->getManager();
        // suppression de l'objet en bdd
        $manager->remove($dispo);
        $manager->flush();

        // redirection
        $this->addFlash('danger', 'La date a bien été supprimé');
        return $this->redirectToRoute('dispo_list');
    }

}  