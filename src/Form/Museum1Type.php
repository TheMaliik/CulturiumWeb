<?php

namespace App\Form;

use App\Entity\Museum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use App\Validator\Constraints as CustomAssert;
use Symfony\Component\Validator\Constraints\NotBlank;



class Museum1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom du musée est obligatoire',
                    ]),
                    new CustomAssert\UniqueMuseumName([
                        'message' => 'Ce nom de musée est déjà utilisé',
                    ]),
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'required' => false,
            ])
            ->add('description')
            ->add('localisation');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Museum::class,
        ]);
    }
}