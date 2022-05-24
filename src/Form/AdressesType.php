<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AdressesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero',IntegerType::class, [
                'required' => true,
                'label' => 'Numero'
            ])
            ->add('nomRue', TextType::class, [
                'required' => true,
                'label' => 'Nom de la rue'
            ])
            ->add('type', TextType::class, [
                'required' => true,
                'label' => 'Type de voie',
                'attr'=> [
                    'placeholder' => 'rue, avenue, place...'
                ], 
            ])
            ->add('codepostal', NumberType::class, [
                'required' => true,
                'attr'=> [
                    'maxLenght' => 5
                ], 
                'label' => 'Code postal'
            ])
            ->add('ville', TextType::class, [
                'required' => true,
                'label' => 'Ville'
            ])

            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
