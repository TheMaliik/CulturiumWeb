<?php

namespace App\Validator\Constraints;
use Symfony\Component\Validator\Constraint;
use App\Entity\Museum;

/**
 * @Annotation
 */
class UniqueMuseumName extends Constraint
{
    public $message = 'Ce nom de musée est déjà utilisé.';
}
