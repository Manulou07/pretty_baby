<?php

namespace App\Form;

use App\Entity\Disponibilite;

use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class DisponibiliteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_dispo', DateType::class, [
                'required' => true,
                'label' => 'Date de disponibilité:',
                // 'format' => 'dd-MM-yyyy',
                'widget' => 'single_text',
            ])
            ->add('isBook')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Disponibilite::class,
        ]);
    }
}
