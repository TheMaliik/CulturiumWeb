<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    public function findByTypeOeuvre($typeOeuvre)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.typeOeuvre = :typeOeuvre')
            ->setParameter('typeOeuvre', $typeOeuvre)
            ->getQuery()
            ->getResult();
    }

    // Add custom repository methods here
}
