<?php

namespace App\Form;

use App\Entity\Events;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
            'required' => false, // ou true, selon vos besoins
            'mapped' => false, // ne pas mapper directement à une propriété de l'entité
            
        ])
        
        ->add('date')
        ->add('nbrPlaceDispo')
        ->add('lieu')
        ->add('note');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }
}
