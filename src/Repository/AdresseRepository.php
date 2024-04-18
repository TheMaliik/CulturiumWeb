<?php

namespace App\Repository;

use App\Entity\Adresse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Adresse>
 *
 * @method Adresse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adresse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adresse[]    findAll()
 * @method Adresse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdresseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adresse::class);
    }

    /**
     * Recherche les adresses par terme de recherche.
     *
     * @param string $searchTerm Le terme de recherche
     * @return Adresse[] Un tableau d'objets Adresse correspondant à la recherche
     */
    public function findBySearchTerm(string $searchTerm): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.idcommande LIKE :searchTerm')
            ->orWhere('a.adresse LIKE :searchTerm')
            ->orWhere('a.ville LIKE :searchTerm')
            ->orWhere('a.codepostal LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->getQuery()
            ->getResult();
    }
}
