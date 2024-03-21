<?php
// src/Repository/UserRepository.php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // Custom method to search users by fullname
    public function searchByFullname(string $fullname): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.fullname LIKE :fullname')
            ->setParameter('fullname', '%' . $fullname . '%')
            ->getQuery()
            ->getResult();
    }

    // Custom method to retrieve users sorted by fullname
    public function findAllSortedByFullname(): array
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.fullname', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
