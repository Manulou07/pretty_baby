<?php

namespace App\Form;

use App\Entity\Realisations;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RealisationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('img', FileType::class, [
                'required' => false,
                'label' => 'Photo Principale',
                'mapped' => false,
                'help' => 'png, jpg, jpeg, jp2 ou webp - 1 Mo maximum',
                'constraints' => [
                    new Image([
                        'maxSize' => '1M',
                        'maxSizeMessage' => 'Le fichier est trop volumineux ({{ size }} Mo). Maximum autorisé : {{ limit }} Mo.',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                            'image/jp2',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Merci de sélectionner une image au format PNG, JPG, JPEG, JP2 ou WEBP'
                    ])
                ]
            ])

            ->add('titre', TextType::class, [
                'required' => true,
                'label' => 'Titre',
                'attr' => [
                    'maxLength' => 100,
                    'placeholder' => 'Ex.: Baby Shower de ...'
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => 'Description',
                'attr' => [
                    'maxLength' => 65535,
                    'placeholder' => 'Ex.:Baby shower sur le thème...'
                ]
            ]);
            for ($i = 2; $i <= 10; $i++) {
                $builder->add('img' . $i, FileType::class, [
                    'required' => false,
                    'label' => 'Photo Supplémentaire' . $i,
                    'mapped' => false,
                    'constraints' => [
                        new Image([
                            'maxSize' => '1M',
                            'maxSizeMessage' => 'Le fichier est trop volumineux ({{ size }} Mo). Maximum autorisé : {{ limit }} Mo.',
                            'mimeTypes' => [
                                'image/png',
                                'image/jpg',
                                'image/jpeg',
                                'image/jp2',
                                'image/webp',
                            ],
                            'mimeTypesMessage' => 'Merci de sélectionner une image au format PNG, JPG, JPEG, JP2 ou WEBP'
                        ])
                    ]
                ]);
            }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Realisations::class,
        ]);
    }
}
