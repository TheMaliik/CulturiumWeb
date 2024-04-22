<?php

namespace App\Form;

use App\Entity\Oeuvre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class OeuvreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
        ->add('description', TextType::class, [
            'required' => false,
        ])
        ->add('nomArtiste', TextType::class, [
            'required' => false,
        ])
        ->add('dateCreation', DateType::class, [
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false,
            'data' => new \DateTime(), // Default date set to today's date
            'constraints' => [
                new NotBlank([
                    'message' => 'Date Creation cannot be blank',
                ]),
                new Type([
                    'type' => \DateTime::class,
                    'message' => 'Date Creation must be a valid date',
                ]),
            ],
        ])
        ->add('reference', TextType::class, [
            'required' => false,
        ])
        ->add('prix', NumberType::class, [
            'required' => false,
        ])
        ->add('nomOeuvre', TextType::class, [
            'required' => false,
        ])
        ->add('typeOeuvre', TextType::class, [
            'required' => false,
        ])
        ->add('linkhttp', TextType::class, [
            'required' => false,
        ])
        ->add('image', FileType::class, [
            'label' => 'Image',
            'mapped' => false,
            'required' => false,
            'attr' => [
                'accept' => 'image/*',
                'onchange' => 'document.getElementById("image-file-name").textContent = this.files[0].name;',
            ],
        ])
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Oeuvre::class,
        ]);
    }
}
