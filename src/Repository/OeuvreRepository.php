<?php

namespace App\Repository;

use App\Entity\Oeuvre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr;

class OeuvreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Oeuvre::class);
    }

public function searchByTerm(string $term): array
{
    return $this->createQueryBuilder('o')
        ->andWhere('o.description LIKE :term OR o.nomArtiste LIKE :term OR o.nomOeuvre LIKE :term')
        ->setParameter('term', '%' . $term . '%')
        ->getQuery()
        ->getResult();
        
}


    // Add custom repository methods here
}
