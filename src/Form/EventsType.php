<?php

namespace App\Form;

use App\Entity\Events;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class EventsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', null, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Le nom du musée est obligatoire',
                ]),
               
            ],
        ])
        ->add('description')
        ->add('image', FileType::class, [
            'label' => 'Event Image',
            'required' => false,
            'mapped' => false,
            'constraints' => [
                new File([
                    'maxSize' => '1024k', // Limite de taille maximale du fichier (ajustez selon vos besoins)
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Veuillez télécharger une image valide (jpg, png, etc.)',
                ]),
            ],
        ])
        
        
        ->add('date')
        ->add('nbrPlaceDispo')
        ->add('lieu')
        ->add('note')
        ->add('idMusee');

        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }
}
