<?php

namespace App\Form;

use App\Entity\Forfait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ForfaitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_forfait', TextType::class, [
                'required' => true,
                'label' => 'Nom Forfait',
                'attr' => [
                    'maxLength' => 100,
                    'placeholder' => 'Ex.: Basic, Premium, Luxe...'
                ]
            ])

            ->add('prix_forfait', NumberType::class, [
                'required' => true,
                'label' => 'Prix Forfait',
                'attr' => [
                    'placeholder' => 'Ex.: Prix sans virgule...'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Forfait::class,
        ]);
    }
}
