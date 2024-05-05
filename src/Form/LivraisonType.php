<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Adresse;

use App\Entity\Livraison;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('dateDeLivraison', DateType::class, [
            'widget' => 'single_text',
            'html5' => false,
            'format' => 'yyyy-MM-dd', // Format d'affichage de la date
            'label' => false, // Supprimer l'étiquette pour la date de livraison
            'attr' => [
                'class' => 'datepicker', // Ajouter une classe pour identifier le champ de date
                'placeholder' => 'Date de livraison',
            ],
            // Ajoutez d'autres options selon vos besoins
        ])
            ->add('statut', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Statut',
                ],
            ])
            ->add('depot', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Dépôt',
                ],
            ])
            ->add('adresse', EntityType::class, [
                'class' => Adresse::class,
                'choice_label' => 'adresse', // La propriété de l'entité Adresse à afficher dans la liste déroulante
                'placeholder' => 'Sélectionnez une adresse', // Texte affiché par défaut dans la liste déroulante
                'label' => false,
                // Vous pouvez ajouter d'autres options selon vos besoins
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}
