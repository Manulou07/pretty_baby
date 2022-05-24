<?php

namespace App\Form;

use App\Entity\Commentaires;
use App\Entity\Realisations;
use Symfony\Component\Form\AbstractType;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comment', TextareaType::class, [
                'required' => true,
                'label' => 'Commentaire',
                'attr' => [
                    'maxLength' => 65535
                ]
            ])
            // ->add('publish', Boolean::class,[
            //     'required' => true,
            //     'label' => 'Publié',
            //     'required' => true,
            //      'empty_value' => false,
            //      'choices_as_values' => true,
            // ])
           
            
            // ->add('fkidrealisations', EntityType::class, [
            //     'required' => true,
            //     'class' => Realisations::class,
            //     'choice_label' => 'titre'
            //  ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaires::class,
        ]);
    }
}
