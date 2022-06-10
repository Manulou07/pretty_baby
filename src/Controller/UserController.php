<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Adresse;
use App\Form\AdressesType;
use App\Entity\Realisations;
use App\Entity\Reservations;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Repository\AdresseRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\CommentairesRepository;
use App\Repository\RealisationsRepository;
use App\Repository\ReservationsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/adresse/liste', name: 'adresse_index')]
    public function adresseIndex(AdresseRepository $adresseRepository): Response
    {
        $adresses = $adresseRepository->findBy(['fk_id_user'=> $this->getUser()]);
        
        return $this->render('user/adresse.html.twig', [
            'adresses' => $adresses,
        ]);
    }

    #[Route('/user/adresse/create', name: 'adresse_create')]
    public function create(Request $request,  ManagerRegistry $managerRegistry)
    {
        $adresse = new Adresse();
        $form = $this->createForm(AdressesType::class, $adresse); 
        $form->handleRequest($request);
      

        if ($form->isSubmitted() && $form->isValid()) {
            $adresse->setFkIdUser($this->getUser());
            $adresse->setAdresseComplete($adresse->getNumero() .' '. $adresse->getType().' '. $adresse->getNomRue()  .' '. $adresse->getCodepostal() .' '. $adresse->getVille());
            $manager = $managerRegistry->getManager();
            $manager->persist($adresse);
            $manager->flush();

            $this->addFlash('success', 'L\'adresse a bien été ajoutée');
            return $this->redirectToRoute('adresse_index');
        }
            return $this->render('user/adresseForm.html.twig', [
            'adresseForm' => $form->createView()
        ]);
    }

    #[Route('user/adresse/delete/{id}', name: 'adresse_delete')]
    public function delete(Adresse $adresse, ManagerRegistry $managerRegistry): Response
    {
        $manager = $managerRegistry->getManager();
        $manager->remove($adresse);
        $manager->flush();

        
        $this->addFlash('danger', 'L\'adresse a bien été supprimé');
        return $this->redirectToRoute('adresse_index');
    }

    #[Route('/user/adresse/update/{id}', name: 'adresse_update')]
    public function update(AdresseRepository $adresseRepository, int $id, Request $request, ManagerRegistry $managerRegistry)
    {
        $adresse = $adresseRepository->find($id);
        $form = $this->createForm(AdressesType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adresse->setAdresseComplete($adresse->getNumero() .' '. $adresse->getType().' '. $adresse->getNomRue()  .' '. $adresse->getCodepostal() .' '. $adresse->getVille());
            $manager = $managerRegistry->getManager();
            $manager->persist($adresse);
            $manager->flush();
            $this->addFlash('success', 'L\'adresse a bien été modifiée');
            return $this->redirectToRoute('adresse_index');
        }
            
        return $this->render('user/adresseForm.html.twig', [
            'adresseForm' => $form->createView()
        ]);
    }

    #[Route('/user/coordonnes/liste', name: 'coordonnee_index')]
    public function coordonneesIndex(UserRepository $userRepository): Response
    {
        $coordonnees = $userRepository->findBy(['id'=> $this->getUser()]);
        
        return $this->render('user/coordonnees.html.twig', [
            'coordonnees' => $coordonnees,
        ]);
    }

    #[Route('user/coordonnees/delete', name: 'coordonnee_delete')]
    public function userDelete(UserRepository $userRepository, ManagerRegistry $managerRegistry): Response
    {
        $user = $userRepository->find($this->getUser());


        $manager = $managerRegistry->getManager();
        
        $this->container->get('security.token_storage')->setToken(null);

        $manager->remove($user);
      
        $manager->flush();

        $this->addFlash('danger', 'Le compte a bien été supprimé');
        return $this->redirectToRoute('home');
 
    }

    #[Route('/user/coordonnee/update/{id}', name: 'coordonnee_update')]
    public function userUpdate(UserRepository $userRepository,UserPasswordHasherInterface $userPasswordHasher, int $id, Request $request, ManagerRegistry $managerRegistry)
    {
        $user = $userRepository->find($this->getUser());
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            
            if($form->get('plainPassword')->getData() == null) {
                $user->setPassword($user->getPassword());
            }else{
               $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            }
            $manager = $managerRegistry->getManager();
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Les coordonnées ont bien été modifiées');
            return $this->redirectToRoute('coordonnee_index');
        }
            
        return $this->render('user/coordonneesForm.html.twig', [
            'coordonneesForm' => $form->createView()
        ]);
    }

    #[Route('/user/reservation/liste', name: 'reservation_index')]
    public function resaIndex(ReservationsRepository $reservationRepository,CommentairesRepository $commentsRepository,RealisationsRepository $realisationRepository): Response
    {
        $reservations = $reservationRepository->findBy(['fk_id_user'=> $this->getUser()]);
        $date = new \DateTime;
                   
        return $this->render('user/reservations.html.twig', [
            'reservations' => $reservations,
            'date' => $date
        ]);
    }

    #[Route('user/reservation/delete/{id}', name: 'reservation_delete')]
    public function deleteresa(Reservations $reservation, ManagerRegistry $managerRegistry): Response
    {
        $manager = $managerRegistry->getManager();
      
        $manager->remove($reservation);
        $manager->flush();
         
        $this->addFlash('danger', 'La reservation a bien été annulé');
        return $this->redirectToRoute('reservation_index');
    }

}
