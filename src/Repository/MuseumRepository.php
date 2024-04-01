<?php

namespace App\Repository;

use App\Entity\Museum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Museum|null find($id, $lockMode = null, $lockVersion = null)
 * @method Museum|null findOneBy(array $criteria, array $orderBy = null)
 * @method Museum[]    findAll()
 * @method Museum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MuseumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Museum::class);
    }
    public function findByName(string $name): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.name LIKE :name')
            ->setParameter('name', '%'.$name.'%')
            ->getQuery()
            ->getResult();
    }
    public function searchByTerm(string $term): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.name LIKE :term')
            ->orWhere('m.description LIKE :term')
            ->orWhere('m.localisation LIKE :term')
            ->setParameter('term', '%'.$term.'%')
            ->getQuery()
            ->getResult();
    }

}
