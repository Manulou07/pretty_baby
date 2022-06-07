<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Forfait;
use App\Entity\Reservations;
use App\Entity\Disponibilite;
use App\Repository\AdresseRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\DisponibiliteRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReservationsType extends AbstractType
{
   
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
 

        $builder
            ->add('date_prestation', EntityType::class, [
                'required' => true,
                'class' => Disponibilite::class,
                'query_builder' => function(DisponibiliteRepository $disponibiliteRepository) {
                    return $disponibiliteRepository->getDatesNonBookees();
                },
                'choice_label' => function ($dispo) {
                    return date_format($dispo->getDateDispo(), 'd/m/Y');
                }
            ])

            ->add('msg_resa', TextareaType::class, [
                'required' => true,
                'label' => 'Message du client'
            ])
              
            ->add('fkIdForfait', EntityType::class, [
                'required' => true,
                'class' => Forfait::class,
                'choice_label' => 'typeForfait'
            ])
            
            ->add('fkIdAdresse', EntityType::class, [
                'required' => true,
                'class' => Adresse::class,
                'query_builder' => function(AdresseRepository $adresse) {
                    return $adresse->getAdresseClient($this->getUser());
                },
                'choice_label' => 'adresseComplete'
                
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservations::class,
        ]);
    }
}
