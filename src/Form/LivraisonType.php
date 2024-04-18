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
            'label' => 'Date de Livraison',
            'html5' => false,
            'format' => 'dd/MM/yyyy', // Format d'affichage de la date
            // Ajoutez d'autres options selon vos besoins
            ])
            ->add('statut', TextType::class)
            ->add('depot', TextType::class)
            // Ajoutez d'autres champs de formulaire si nécessaire
            ->add('adresse', EntityType::class, [
                'class' => Adresse::class,
                'choice_label' => 'adresse', // La propriété de l'entité Adresse à afficher dans la liste déroulante
                'placeholder' => 'Sélectionnez une adresse', // Texte affiché par défaut dans la liste déroulante
                // Vous pouvez ajouter d'autres options selon vos besoins
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}
