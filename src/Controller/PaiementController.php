<?php

namespace App\Controller;

use Stripe\StripeClient;
use App\Repository\ReservationsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaiementController extends AbstractController
{
    #[Route('/paiement', name: 'paiement')]
    public function index(SessionInterface $sessionInterface, ReservationsRepository $reservationsRepository): Response
    {
        $cart = $sessionInterface->get('resa'); // récupération du panier en session
        $stripeCart = []; // initialisation du panier pour Stripe
        $resa = $reservationsRepository->find($cart->getId());
        $stripeElement = [
            'amount' => $resa->getFkIdForfait()->getPrixForfait() * 100,
            'quantity' => 1,
            'currency' => 'EUR',
            'name' => $resa->getFkIdForfait()->getTypeForfait()
        ];
        
        $stripeCart[] = $stripeElement;
        $key ='sk_test_51KdBVWCsyiWxbbVUe7OFHzgeO7y92Tp8mjF75UjgXwteMTXzoENKXYWgqv426lpmvy46pdd2VNMc1naOl9pB7KBw00U5BzElzG';
        $stripe = new StripeClient($key);

        $stripeSession = $stripe->checkout->sessions->create([
            'line_items' => $stripeCart,
            'mode' => 'payment',
            'success_url' => 'https://127.0.0.1:8000/paiement/success',
            'cancel_url' => 'https://127.0.0.1:8000/paiement/cancel',
            'payment_method_types' => ['card']
        ]);

        return $this->render('paiement/index.html.twig', [
            'sessionId' => $stripeSession->id
        ]);
    }

    #[Route('/paiement/success', name: 'paiement_success')]
    public function success(Request $request, SessionInterface $sessionInterface): Response
    {
        if ($request->headers->get('referer') !== 'https://checkout.stripe.com/') {
            return $this->redirectToRoute('resa_panier');
        }
        // générer une facture
        // envoyer un mail de confirmation de commande avec la facture en pièce-jointe
        $sessionInterface->remove('resa'); // vide le panier
        return $this->render('paiement/success.html.twig');
    }

    #[Route('/paiement/cancel', name: 'paiement_cancel')]
    public function cancel(Request $request): Response
    {
        if ($request->headers->get('referer') !== 'https://checkout.stripe.com/') {
            return $this->redirectToRoute('resa_panier');
        }
        return $this->render('paiement/cancel.html.twig');
    }
}
