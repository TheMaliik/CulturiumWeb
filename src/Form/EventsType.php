<?php

namespace App\Form;

use App\Entity\Events;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name')
        ->add('description')
        ->add('imageFile', FileType::class, [ // Ajout du champ imageFile de type FileType
            'label' => 'Event Image',
            'mapped' => false, // Cela signifie que ce champ n'est pas associé directement à une propriété de l'entité
            'required' => false, // Le champ n'est pas obligatoire
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
