<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {

      
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData(); 
            $email = (new TemplatedEmail())
                ->from(new Address($contact['email'], $contact['prenom'] . ' ' . $contact['nom']))
                ->to(new Address('desousa.emmanuel@gmail.com'))
                ->subject('PRETTY BABY - demande de contact - ' . $contact['objet'])
                ->htmlTemplate('contact/contact.html.twig') 
                ->context([ 
                    'prenom' => $contact['prenom'],
                    'nom' => $contact['nom'],
                    'adresseEmail' => $contact['email'],
                    'telephone' => $contact['telephone'],
                    'objet' => $contact['objet'],
                    'message' => $contact['message'],
                ]);
           
            $mailer->send($email);
            $this->addFlash('success', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('contact');

        }
        
        return $this->render('contact/index.html.twig',[
           'contactForm' => $form->createView()
        ]);
    }
}