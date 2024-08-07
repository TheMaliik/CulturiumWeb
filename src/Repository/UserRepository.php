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

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function searchByEmail(string $email): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.email LIKE :email')
            ->setParameter('email', '%' . $email . '%')
            ->getQuery()
            ->getResult();
    }

    public function searchByEmail2(string $email): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }
    


// Tri Email 
    public function findAllSortedByEmail(): array
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.email', 'ASC')
            ->getQuery()
            ->getResult();
    }









// For Stat
    // Custom method to count approved users
    public function countApprovedUsers(): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.isApproved = true')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Custom method to count blocked users
    public function countBlockedUsers(): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.isBlocked = true')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // Custom method to count all users
    public function countAllUsers(): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }


    // End For Stat


    public function findBlockedOrApprovedUsers()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.isBlocked = :blocked OR u.isApproved = :approved')
            ->setParameter('blocked', true)
            ->setParameter('approved', true)
            ->getQuery()
            ->getResult();
    }

    

}
