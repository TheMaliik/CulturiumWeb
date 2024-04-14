<?php

namespace App\Validator\Constraints;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Constraint;
use App\Validator\Constraints\UniqueMuseumName;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Museum;


class UniqueMuseumNameValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
          // Vérifier si un musée avec le même nom existe déjà
          $existingMuseum = $this->entityManager->getRepository(Museum::class)->findOneBy(['name' => $value]);

          if ($existingMuseum) {
              // S'il existe, ajouter une erreur de validation
              $this->context->buildViolation($constraint->message)
                  ->addViolation();
          }
      }
}
