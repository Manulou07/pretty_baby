<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class,[
            'required' => true,
            'attr' => [
                'maxlenght' => 100
            ]
        ])
        ->add('prenom', TextType::class,[
            'required' => true,
            'attr' => [
                'maxlenght' => 100
            ]
        ])
        ->add('email', EmailType::class,[
            'required' => true,
            'attr' => [
                'maxlenght' => 100
            ]
        ])
        ->add('telephone', NumberType::class,[
            'required' => false,
            'attr' => [
                'maxlenght' => 10
            ]
        ])
        
        ->add('objet', ChoiceType::class, [
            'required' => true,
            'attr' => [
                'maxlenght' => 255
            ],
            'choices' => [
                '- Selectionnez une demande -' => '',
                'Question sur les prestations' => 'prestation',
                'Demander une facture' => 'facture',
                'Signaler un problème' => 'problème',
                'Autre demande' => 'autre'
            ]

        ])

        ->add('message', TextareaType::class, [
            'required' => true,
            'attr' => [
                'minLenght' => 50,
                'maxLenght' => 1000
            ],
            'help' => '1000 caractères maximum'
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
