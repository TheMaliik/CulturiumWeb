<?php

namespace App\Form;
use App\Entity\Commande;
use App\Entity\Oeuvre;
use App\Entity\Panier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class PanierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('idOeuvre', EntityType::class, [
            'class' => Oeuvre::class, // Utilisez le nom complet de la classe Author
            'choice_label' => 'reference', // Utilisez la propriété 'username' de l'entité Author
            'multiple' => false,
            'expanded' => false,
            'required' => false,
            'placeholder' => 'Select an oeuvre'
        ])
            ->add('idCommand', EntityType::class, [
                'class' => Commande::class, // Utilisez le nom complet de la classe Author
                'choice_label' => 'contenue', // Utilisez la propriété 'username' de l'entité Author
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                'placeholder' => 'Select an commande'
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3([
                    'message' => 'reCAPTCHA validation failed. Please try again.',
                ]),
                'action_name' => 'contact',
               
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Panier::class,
        ]);
    }
}
