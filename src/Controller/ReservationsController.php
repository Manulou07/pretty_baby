<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Adresse;
use App\Form\AdressesType;
use App\Entity\Reservations;
use App\Form\ReservationsType;
use App\Repository\AdresseRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ReservationsRepository;
use App\Repository\DisponibiliteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationsController extends AbstractController
{
   #[Route('/admin/reservations', name: 'resa_list')]
    public function list(ReservationsRepository $reservationsRepository): Response
    {
        $reservations = $reservationsRepository->findAll();
        
        return $this->render('admin/reservations.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/admin/reservations/{id}', name: 'resa_list_plus')]
    public function details(ReservationsRepository $reservationsRepository, int $id): Response
    {
        $reservation = $reservationsRepository->find($id);
        
        return $this->render('admin/resaplus.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/admin/reservations/delete/{id}', name: 'resa_delete')]
    public function delete(Reservations $reservations, ManagerRegistry $managerRegistry): Response
    {
        $manager = $managerRegistry->getManager();

        // suppression de l'objet en bdd
        $manager->remove($reservations);
        $manager->flush();

        // redirection
        $this->addFlash('danger', 'La reservation a bien été supprimé');
        return $this->redirectToRoute('resa_list');
    }
    
    #[Route('/reservations', name: 'resa_index')]
    public function index(): Response
    {
        return $this->render('reservations/index.html.twig', [
            'controller_name' => 'Controller',
        ]);
    }

    #[Route('/reservations/adresse', name: 'resa_adresse')]
    public function adresseindex(Request $request,ManagerRegistry $managerRegistry,AdresseRepository $adressesRepository)
    {   
        $adresses = $adressesRepository->findBy(['fk_id_user'=> $this->getUser()]);
       
        if ($adresses != null){
           
            return $this->redirectToRoute('resa_date');

        }
            $adresse = new Adresse();
            $formadresse = $this->createForm(AdressesType::class, $adresse); 
            $formadresse->handleRequest($request);
            

            if ($formadresse->isSubmitted() && $formadresse->isValid()) {
                
                $adresse->setFkIdUser($this->getUser());
                $adresse->setAdresseComplete($adresse->getNumero() .' '. $adresse->getType().' '. $adresse->getNomRue()  .' '. $adresse->getCodepostal() .' '. $adresse->getVille());
                $manager = $managerRegistry->getManager();
                $manager->persist($adresse);
                $manager->flush();  
                
                return $this->redirectToRoute('resa_date');
            }
          

             return $this->render('reservations/adresse.html.twig', [
            'resaFormadresse' => $formadresse->createView()
            ]);
                    
    }

    #[Route('/reservations/adresse/create', name: 'resa_adresse_create')]
    public function create(Request $request, ManagerRegistry $managerRegistry)
    {   
            $adresse = new Adresse();
            $formadresse = $this->createForm(AdressesType::class, $adresse); 
            $formadresse->handleRequest($request);

            if ($formadresse->isSubmitted() && $formadresse->isValid()) {
                
                $adresse->setFkIdUser($this->getUser());
                $adresse->setAdresseComplete($adresse->getNumero() .' '. $adresse->getType().' '. $adresse->getNomRue()  .' '. $adresse->getCodepostal() .' '. $adresse->getVille());
                $manager = $managerRegistry->getManager();
                $manager->persist($adresse);
                $manager->flush();
                return $this->redirectToRoute('resa_date');
            }

             return $this->render('reservations/adresse.html.twig', [
            'resaFormadresse' => $formadresse->createView()
            ]);
               
    }
     
    // #[Route('/reservations/adresselist', name: 'resa_adressebis')]
    // public function createbis(Request $request,ManagerRegistry $managerRegistry, AdresseRepository $adressesRepository,SessionInterface $sessionInterface)
    // {   
    //     $adresse = new Adresse();
    //     $formadresse = $this->createForm(AdressesType::class, $adresse); 
    //     $formadresse->handleRequest($request);

    //     if($formadresse->isSubmitted() && $formadresse->isValid()){

    //         $adresses = $adressesRepository->find($adresse->getId());
    //         $adresse->setFkIdUser($this->getUser());
    //         $manager = $managerRegistry->getManager();
    //         $manager->persist($adresse);
    //         $manager->flush();
    //         $sessionInterface->set('adresse', $adresses->getId());
    //         return $this->redirectToRoute('resa_date');
    //     }
            
    //     return $this->render('reservations/adressebis.html.twig', [
    //         'resaFormadresse' => $formadresse->createView()
    //     ]);
    // }
         
     
     
    #[Route('/reservations/date', name: 'resa_date')]
    public function date(AdresseRepository $adresseRepository, DisponibiliteRepository $dispoRepository,SessionInterface $sessionInterface, Request $request, ManagerRegistry $managerRegistry)
    {          
        $resa = new Reservations();
        $resa->setFkIdUser($this->getUser());
       
        $form = $this->createForm(ReservationsType::class, $resa); 
        $form->handleRequest($request);  
       
        if ($form->isSubmitted() && $form->isValid()) {  

            $resa->setDateResa(new \DateTime);
            $id = $resa->getDatePrestation();
            $dispo = $dispoRepository->find($id);
            $dispo->setIsBook(true);
            
            $manager = $managerRegistry->getManager();
            $manager->persist($resa);
            
            $sessionInterface->set('resa', $resa);
            $manager->flush();
            
            return $this->redirectToRoute('resa_panier');
           
            }
                       
            return $this->render('reservations/date.html.twig', [
            'resaForm' => $form->createView(),

        ]);
    }

    #[Route('/reservations/paiement', name: 'resa_panier')]
    public function panier(SessionInterface $sessionInterface): Response
    {   
        $cart= $sessionInterface->get('resa', []); 
       
        return $this->render('reservations/panier.html.twig', [
            'cart' => $cart,
           
        ]);
            
          
    }

}
